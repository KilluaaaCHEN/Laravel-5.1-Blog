<?php
namespace App\Helper;

use Illuminate\Routing\Controller;


class Path
{
    /**
     * 获取当前路由的别名
     * @return mixed
     */
    public static function getAlias()
    {
        $url = url()->current();
        $url = explode('/', $url);
        return $url[3] . '.' . $url[4];
    }
}