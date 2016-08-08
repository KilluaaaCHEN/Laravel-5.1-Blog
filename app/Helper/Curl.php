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

    /**
     * post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        return $result;
    }
}
