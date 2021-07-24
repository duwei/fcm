<?php

function reload_redis_connections()
{
    app()->singleton('redis', function ($app) {
        $config = $app->make('config')->get('database.redis', []);
        return new \Illuminate\Redis\RedisManager($app, \Illuminate\Support\Arr::pull($config, 'client', 'phpredis'), $config);
    });
    \Illuminate\Support\Facades\Facade::clearResolvedInstance('redis');
}

function set_redis_connection($name, $config)
{
    config(["database.redis.$name" =>
        [
            'host' => $config['host'],
            'password' => $config['password'],
            'port' => $config['port'],
            'database' => $config['database']
        ]
    ]);
    reload_redis_connections();

}

function verify_id_card($name, $id_card){
// 云市场分配的密钥Id
    $secretId = 'AKID1uQPDJcE32CDy7dUf277z0O0e8lGXFnV5bF8';
// 云市场分配的密钥Key
    $secretKey = '22ka52o4C39h3Ta7zuPchkkzokqpipezasu2ea9E';
    $source = 'market';

// 签名
    $datetime = gmdate('D, d M Y H:i:s T');
    $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
    $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
    $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);

// 请求方法
    $method = 'GET';
// 请求头
    $headers = array(
        'X-Source' => $source,
        'X-Date' => $datetime,
        'Authorization' => $auth,
    );
// 查询参数
    $queryParams = array(
//    'cardNo' => '330329199001020022',
        'cardNo' => $id_card,
//    'realName' => '张三'
        'realName' => $name
    );
// body参数（POST方法下）
    $bodyParams = array();
// url参数拼接
    $url = 'http://service-18c38npd-1300755093.ap-beijing.apigateway.myqcloud.com/release/idcard/VerifyIdcardv2';
    if (count($queryParams) > 0) {
        $url .= '?' . http_build_query($queryParams);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
        return $k . ': ' . $v;
    }, array_values($headers), array_keys($headers)));
    if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
    }

    $data = curl_exec($ch);
    if (curl_errno($ch)) {
//        echo "Error: " . curl_error($ch);
        return ["error_code" => 1, "reason" => "访问服务器失败"];
    } else {
//        print_r($data);
    }
    curl_close($ch);
    return json_decode($data, true);
}
