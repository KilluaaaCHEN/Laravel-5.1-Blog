<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Log;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
//        $wechat = app('wechat');
//        $wechat->server->setMessageHandler(function ($message) {
//            return "欢迎关注:" . $message;
//        });
//
//        Log::info('return response.');
//
//        return $wechat->server->serve();

        $options = [
            'debug' => true,
            'app_id' => env('WECHAT_APPID'),
            'secret' => env('WECHAT_SECRET'),
            'token' => env('WECHAT_TOKEN'),
            'log' => [
                'level' => 'debug',
                'file' => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
        ];
        $app = new Application($options);
        $app->server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!".$message;
        });
        $response = $app->server->serve();
        return $response;
    }

}
