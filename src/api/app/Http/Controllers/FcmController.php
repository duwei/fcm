<?php

namespace App\Http\Controllers;

use App\Jobs\AddMasUserCommand;
use App\Models\TokenData;
use Doctrine\DBAL\Driver\PDOException;
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

    /**
     * @OA\Get(
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
//        $validator = Validator::make($request->all(), [
//            'WorldID' => 'required|string'
//        ]);
//
//        if ($validator->fails()) {
//            return $this->makeBadRequestResponse();
//        }

        $ret = [];
        return $this->makeApiResponse($ret);
    }

    /**
     * @OA\Get(
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

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"FcmClient"},
     *     summary="user login",
     *     operationId="login",
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
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"GameClient"},
     *     summary="user login",
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
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"FcmClient"},
     *     summary="user login",
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
     *     ref="#/components/schemas/UserStatus",
     *      )
     *     )
     *    ),
     * )
     */
}
