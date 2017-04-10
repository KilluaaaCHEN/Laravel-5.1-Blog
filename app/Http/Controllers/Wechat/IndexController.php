<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;


class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        $options = [
            'debug' => true,
            'app_id' => 'wxb1aa3893517eb877',
            'secret' => '793e8497faf5306522c991acc844b1d4',
            'token' => 'larry666',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file' => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
        ];
        $app = new Application($options);
        $response = $app->server->serve();
// 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
//        return $response;
    }

}
