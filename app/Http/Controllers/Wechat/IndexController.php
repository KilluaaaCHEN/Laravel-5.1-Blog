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
                    switch ($message->Event) {
                        case 'subscribe':// 关注事件
                            // 扫描带参数二维码事件 用户未关注时，进行关注后的事件推送
                            if (!empty($message->EventKey) && !empty($message->Ticket)) {
                                $scene_id = str_ireplace('qrscene_', '', $message->EventKey);
                                return "用户未关注时\n场景:$scene_id,Tickey:$message->Ticket\n$message";
                            } else {
                                return "欢迎关注Larry WeChat Test\n$message";
                            }
                            break;
                        case 'SCAN':// 扫描带参数二维码事件 用户已关注时的事件推送
                            return "用户已关注时的事件推送\n场景:$message->EventKey,Tickey:$message->Ticket\n$message";
                            break;
                        case 'unsubscribe':
                            return "取消关注\n$message";
                            break;
                        case 'CLICK':// 自定义菜单事件推送
                            break;
                        case 'LOCATION':
                            return "地理位置纬度:$message->Location_X
                            地理位置经度 $message->Location_Y
                            地图缩放大小 $message->Scale
                            地理位置信息 $message->Label\n$message";
                            break;
                        case 'scancode_push':// 自定义菜单事件推送 -scancode_push：扫码推事件的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'scancode_waitmsg': // 自定义菜单事件推送 -scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'pic_sysphoto':// 自定义菜单事件推送 -pic_sysphoto：弹出系统拍照发图的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'pic_photo_or_album':// 自定义菜单事件推送 -pic_photo_or_album：弹出拍照或者相册发图的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'pic_weixin':// 自定义菜单事件推送 -pic_weixin：弹出微信相册发图器的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'location_select':// 自定义菜单事件推送 -location_select：弹出地理位置选择器的事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'MASSSENDJOBFINISH': // 事件推送群发结果
                            return PHP_EOL . $message;
                            break;
                        case 'TEMPLATESENDJOBFINISH':// 事件推送模版消息发送结果
                            return PHP_EOL . $message;
                            break;
                        case 'user_pay_from_pay_cell':// 买单事件推送
                            return PHP_EOL . $message;
                            break;
                        case 'qualification_verify_success':// 资质认证成功
                        case 'qualification_verify_fail':// 资质认证失败
                        case 'naming_verify_success':// 名称认证成功
                        case 'naming_verify_fail':// 名称认证失败
                        case 'annual_renew':// 年审通知
                        case 'verify_expired':// 认证过期失效通知
                            return '资质通知' . PHP_EOL . $message;
                            break;
                        default:
                            return '未知类型' . PHP_EOL . $message;
                            break;
                    }
                    break;
                case 'text':
                    return '收到文字消息' . $message;
                    break;
                case 'image':
                    return PHP_EOL . $message;
                    break;
                case 'voice':
                    return PHP_EOL . $message;
                    break;
                case 'video':
                    return PHP_EOL . $message;
                    break;
                case 'location':
                    return PHP_EOL . $message;
                    break;
                case 'link':
                    return PHP_EOL . $message;
                    break;
                default:
                    return '收到其它消息' . PHP_EOL . $message;
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
        $result = $wechat->oauth->notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        var_dump($result);
    }

    public function reply(Application $wechat)
    {
        $reply = $wechat->reply;


        dd($reply->current());
    }

    public function qrCode(Application $wechat)
    {
        $qrcode = $wechat->qrcode;
        $result = $qrcode->forever('back_sign');
        $ticket = $result->ticket;// 或者 $result['ticket']
        $expireSeconds = $result->expire_seconds; // 有效秒数
        $url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片

        dd($ticket, $expireSeconds, $url, $qrcode->url($ticket));

        $url = $qrcode->url($ticket);


    }

}
