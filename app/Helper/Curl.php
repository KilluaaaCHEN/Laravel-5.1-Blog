<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 15/11/6
 * Time: 11:15
 */

namespace App\Helper;

class Curl
{
    /**
     * get请求
     * @param $url
     * @return mixed
     */
    public static function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        return  curl_exec($ch);
    }
}
