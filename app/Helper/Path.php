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
        $action = Controller::getRouter()->current()->getAction();
        if (isset($action['as'])) {
            return substr($action['as'], 0, strrpos($action['as'], '.'));
        }
        return null;
    }
}