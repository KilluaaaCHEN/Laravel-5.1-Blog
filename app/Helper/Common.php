<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 16/6/2
 * Time: 14:26
 */

namespace App\Helper;


use App\Models\Dict;

class Common
{
    /**
     * 百度翻译
     * @author Killua Chen
     * @param $query
     * @param $attr_list
     * @return bool
     */
    public static function bdTranslateAction($query, &$attr_list)
    {
        try {
            $query_list = explode('_', str_replace(' ', '_', str_replace('-', '_', self::humpToLine($query))));
            $result = '';
            foreach ($query_list as $item) {
                if (array_key_exists($item, $attr_list)) {
                    $result .= $attr_list[$item];
                    continue;
                }
                $result .= self::translate($item);
            }
            $dict_service = new Dict();
            $dict_service->fill(['key' => strtolower($query), 'val' => $result]);
            $dict_service->save();
            $attr_list[$query] = $result;
            return $result;
        } catch (\Exception $e) {
            return '&nbsp;';
        }
    }

    /*
 * 驼峰转下划线
 */
    public static function humpToLine($name)
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $name));
    }

    public static function translate($query)
    {
        $url = 'http://api.fanyi.baidu.com/api/trans/vip/translate';
        $param = [
            'q' => $query,
            'from' => 'en',
            'to' => 'zh',
            'appid' => '20180104000111917',
            'salt' => rand(999, 9999),
        ];
        $serct = 'lDpKqIbqnqpm5clf_zdd';
        $param['sign'] = md5($param['appid'] . $param['q'] . $param['salt'] . $serct);
        $data = json_decode(Curl::post($url, $param), true);
        return $data['trans_result'][0]['dst'];
    }

}
