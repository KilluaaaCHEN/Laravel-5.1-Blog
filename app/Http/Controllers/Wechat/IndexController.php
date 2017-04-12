<?php

namespace App\Http\Controllers\Wechat;

use EasyWeChat\Message\Image;
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
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息' . $message;
                    break;
                case 'text':
                    return '收到文字消息' . $message;
                    break;
                case 'image':
                    return new Image(['media_id' => 'l0g2_vWTfcUyRSefKF4iDrUuBSxfl-GjEHTVykPu2_hMq1TNCyYTplZFwoEA_pmz']);
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        Log::info('return response.');
        return $wechat->server->serve();
    }

    public function menu(Application $wechat)
    {
        $menu = $wechat->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "今日歌曲",
                "key" => "V1001_TODAY_MUSIC"
            ],
            [
                "name" => "菜单122223",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url" => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url" => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        $menus = $menu->all();
        dd($menus);
    }

    public function notice(Application $wechat)
    {
        $userId = 'o7TeK040ZuriQze6k7rhmzv9aj_w';
        $templateId = 'k4mOTPLWew2uL3nyNJWYAjNIRy3AEro4SoACZlT0iuo';
        $url = 'http://larry666.com/view/35';

        $data = array(
            "first" => array("恭喜你购买成功！", '#555555'),
            "name" => array("巧克力", "#ccc"),
            "price" => array("39.8元", "#FF0000"),
            "remark" => array("欢迎再次购买！", "#5599FF"),
        );
        $result = $wechat->notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        var_dump($result);
    }

    public function reply(Application $wechat)
    {
        dd($wechat->reply->current());
    }

}
