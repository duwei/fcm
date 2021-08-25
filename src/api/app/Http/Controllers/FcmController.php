<?php

namespace App\Http\Controllers;

use App\Jobs\AddMasUserCommand;
use App\Models\Game;
use App\Models\GameUser;
use App\Models\TokenData;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Feature_Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Likelihood;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Info(
 *  title="FCMApi",
 *  version="0.1.0",
 *   @OA\Contact(
 *     email="duwei@wemade.com"
 *   )
 * )
 */
class FcmController extends Controller
{
    const RET_OK              = 0;
    const BAD_REQUEST         = 1;
    const UNAUTHORIZED        = 2;
    const MAINTENANCE         = 3;
    const DUPLICATED          = 4;
    const SERVER_ERROR        = 5;
    const VERIFY_FAILED       = 6;
    const OUT_OF_TIME         = 7;
    const LOGIN_FAILED        = 8;

    private function makeResponse($code, $msg, $data = null)
    {
        $ret = [
            'code' => $code,
            'msg' => $msg,
        ];
        if (!empty($data)) $ret['data'] = $data;
        return response()->json($ret, 200);
    }

    private function makeApiResponse($data)
    {
        return $this->makeResponse(self::RET_OK, 'OK', $data);
    }

    private function makeBadRequestResponse()
    {
        return $this->makeResponse(self::BAD_REQUEST, 'Bad Request');
    }

    private function makeUnauthorizedResponse()
    {
        return $this->makeResponse(self::UNAUTHORIZED, 'Unauthorized');
    }

    private function getRemain($game_user)
    {
//        $game_user['remain_time_quota'] = 2.5;
//        $game_user['remain_money_quota'] = 100;
        return $game_user;
    }

    /**
     * @OA\Post(
     *     path="/api/get_game",
     *     tags={"GameServer"},
     *     summary="get game configuration",
     *     operationId="get_game",
     *     @OA\RequestBody(ref="#/components/requestBodies/GetGame"),
     *
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     @OA\Property(
     *     property="data",
     *     type="object",
     *     ref="#/components/schemas/Game",
     *      )
     *     )
     *    ),
     * )
     */
    public function getGame(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->makeBadRequestResponse();
        }

        $game = Game::where('uuid', $request->uuid)->first();
        return $this->makeApiResponse($game);
    }

    /**
     * @OA\Post(
     *     path="/api/get_user",
     *     tags={"GameClient"},
     *     summary="get user status",
     *     operationId="get_user",
     *     @OA\RequestBody(ref="#/components/requestBodies/GetUser"),
     *
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     @OA\Property(
     *     property="data",
     *     type="object",
     *     ref="#/components/schemas/UserStatus",
     *      )
     *     )
     *    ),
     * )
     */
    public function getUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->makeBadRequestResponse();
        }

        $account = Redis::get($request->access_token);
        $game_user = GameUser::where(['account' => $account])->first();
        if ($game_user) {
            return $this->makeApiResponse($this->getRemain($game_user));
        }
        return $this->makeBadRequestResponse();
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"FcmClient"},
     *     summary="user login",
     *     operationId="login",
     *     @OA\RequestBody(ref="#/components/requestBodies/Login"),
     *
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     @OA\Property(
     *     property="data",
     *     type="object",
     *      @OA\Property(
     *          property="title",
     *          type="string",
     *          description="login prompt title"
     *      ),
     *      @OA\Property(
     *          property="content",
     *          type="string",
     *          description="login prompt content"
     *      ),
     *      @OA\Property(
     *          property="access_token",
     *          type="string",
     *          description="access token"
     *      ),
     *      )
     *     )
     *    ),
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->makeBadRequestResponse();
        }

        $game = Game::findOrFail(1);
        $game_user = GameUser::where('account', $request->account)->first();
        if ($game_user && app('hash')->check($request->password, $game_user->password))
        {
            $prev_token = Redis::get($game_user->account);
            Redis::del($prev_token);

            if ($game_user->age < 18) {
                $open_time = date_create_from_format('H:i:s', $game->open_time);
                $close_time = date_create_from_format('H:i:s', $game->close_time);
                $now = date_create();
//                return $this->makeResponse(self::OUT_OF_TIME, date_format($now, 'H i s'));
                if ($now < $open_time || $now > $close_time) {
                    return $this->makeResponse(self::OUT_OF_TIME, $game->out_of_time_prompt);
                }
            }

            $access_token = Str::uuid()->toString();
            Redis::set($access_token, $game_user->account);
            Redis::set($game_user->account, $access_token);
            $ret = [
                'access_token' => $access_token
            ];
            if ($game_user->age < 18) {
                $content = $game->start_prompt;
                $ret['title'] = '未成年防沉迷';
                $ret['content'] = $content;
            }
            return $this->makeApiResponse($ret);
        }
        return $this->makeResponse(self::LOGIN_FAILED, '登陆失败');
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"GameClient"},
     *     summary="user logout",
     *     operationId="logout",
     *     @OA\RequestBody(ref="#/components/requestBodies/GetUser"),
     *
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     )
     *    ),
     * )
     */
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->makeBadRequestResponse();
        }

        $account = Redis::get($request->access_token);
        Redis::del($account);
        Redis::del($request->access_token);
        return $this->makeApiResponse([]);
    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"FcmClient"},
     *     summary="user register",
     *     operationId="register_user",
     *     @OA\RequestBody(ref="#/components/requestBodies/RegisterUser"),
     *
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     @OA\Property(
     *     property="data",
     *     type="object",
     *      @OA\Property(
     *          property="title",
     *          type="string",
     *          description="login prompt title"
     *      ),
     *      @OA\Property(
     *          property="content",
     *          type="string",
     *          description="login prompt content"
     *      ),
     *      @OA\Property(
     *          property="access_token",
     *          type="string",
     *          description="access token"
     *      ),
     *      )
     *     )
     *    ),
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string',
            'id_card' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->makeBadRequestResponse();
        }
        $ret = verify_id_card($request->name, $request->id_card);
        $game = Game::findOrFail(1);
        $age = 8;
        if ($ret['error_code'] != 0) {
            return $this->makeResponse(self::SERVER_ERROR, $ret['reason']);
        } elseif (!$ret['result']['isok']) {
//            return $this->makeResponse(self::VERIFY_FAILED, '姓名身份证号码不匹配');
        } elseif ($ret['result']['IdCardInfor'] && $ret['result']['IdCardInfor']['birthday'] ) {
            $start = date_create_from_format('Y-m-d',$ret['result']['IdCardInfor']['birthday']);
            $now = date_create();
            $diff = $start->diff($now);
            $age = $diff->format('%y');
        }
        // todo: 身份证号码唯一绑定

//        try {
            $game_user = GameUser::firstOrCreate(['account' => $request->account],
                array_merge($request->post(), ['game_id' => 1, 'age' => $age]));
//        } catch (\Exception $e) {
//            return $this->makeBadRequestResponse();
//        }
        if ($game_user->wasRecentlyCreated) {
            if ($game_user->age < 18) {
                $open_time = date_create_from_format('H:i:s', $game->open_time);
                $close_time = date_create_from_format('H:i:s', $game->close_time);
                $now = date_create();
//                return $this->makeResponse(self::OUT_OF_TIME, date_format($now, 'H i s'));
                if ($now < $open_time || $now > $close_time) {
                    $prompt = "注册成功<br>" . $game->out_of_time_prompt;
                    return $this->makeResponse(self::OUT_OF_TIME, $prompt);
                }
            }
            $access_token = Str::uuid()->toString();
            Redis::set($access_token, $game_user->account);
            Redis::set($game_user->account, $access_token);
            $ret = [
                'access_token' => $access_token
            ];
            if ($age < 18) {
                $content = Game::find(1)->start_prompt;
                $ret['title'] = '未成年防沉迷';
                $ret['content'] = $content;
            }
            return $this->makeApiResponse($ret);
        } else {
            return $this->makeResponse(self::DUPLICATED, '重复注册');
        }
    }
    public function success(){
    }

    public function agreement(){
        $game = Game::findOrFail(1);
        return $game->agreement;
    }

    /**
     * @OA\Post(
     *     path="/api/image_objects",
     *     tags={"GoogleApi"},
     *     summary="get image objects",
     *     operationId="image_objects",
     *     @OA\RequestBody(
     *         description="Upload image request body",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="image to detect",
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *                 required={"image"}
     *             )
     *         )
     *     ),
     *    @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *     @OA\Property(
     *     property="code",
     *     type="integer",
     *     description="status code: success => 0, fail => other number"
     *     ),
     *     @OA\Property(
     *     property="msg",
     *     type="string",
     *     description="status message",
     *     example="OK",
     *     ),
     *     @OA\Property(
     *     property="data",
     *     type="array",
     *       @OA\Items(
     *         @OA\Property(
     *           property="name",
     *           type="string",
     *           description="object name"
     *         ),
     *         @OA\Property(
     *           property="score",
     *           type="number",
     *           description="object score"
     *         ),
     *       )
     *      )
     *     )
     *    ),
     * )
     */
    public function getImageObjects(Request $request){
//        putenv("GOOGLE_APPLICATION_CREDENTIALS=/storage/app/service-account.json");
//        $request->file('file')->store('public');
        if (!$request->hasFile('image')) {
            return $this->makeBadRequestResponse();
        }
        $filePath = base_path('public/uploads/') . date('Ymd');
        if (!file_exists($filePath)) {
            mkdir($filePath);
        }
        $extension = $request->file('image')->extension();
//        $fileName = uniqid() . '.' . $extension;
        $fileName = sha1_file( request()->file('image')->path()) . '.' . $extension;
        $request->file('image')->move($filePath, $fileName);

        $client = new ImageAnnotatorClient([
            'credentials' => storage_path('app/service-account.json')
        ]);

        $annotation = $client->annotateImage(
            fopen($filePath . '/' . $fileName,'r'),
            [Type::OBJECT_LOCALIZATION]
        );

        $ret = [];
        foreach ($annotation->getLocalizedObjectAnnotations() as $localizedObjectAnnotation) {
            $ret []= array(
                'name' => $localizedObjectAnnotation->getName(),
                'score' => $localizedObjectAnnotation->getScore()
            );
        }
        return $this->makeApiResponse($ret);
    }
}
