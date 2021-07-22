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
