<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Dict;
use Illuminate\Http\Request;

class DocController extends Controller
{

    /**
     * 生成Markdown形式的API文档
     * @param Request $request
     * @return mixed
     */
    public function generate(Request $request)
    {
        $title = $request->get('title');
        $uri = $request->get('uri');
        $method = $request->get('method');
        $req = $request->get('request');
        $res = $request->get('response');
        $attr = $request->get('attr');
        $doc = '';
        if ($request->getMethod() == 'POST') {
            $uri = ltrim($uri, '{{domain}}');
            $req_list = explode(PHP_EOL, $req);
            $res_list = json_decode($res, true);

            //查询字典
            $cache_key = 'dict';
            $attr_list = \Cache::get($cache_key);
            if (!$attr_list) {
                $attr_list = Dict::lists('val', 'key')->toArray();
                \Cache::put($cache_key, $attr_list, 60 * 24);
            }

            $attr_dic = json_decode($attr, true);
            if ($attr_dic) {
                $attr_list = array_merge($attr_dic, $attr_list);
            }
            $doc = <<<STR
###  $title 
| URI | $uri  |   |   |   | 
| :----: | ------------ | ------------ | ------------ |  :----: |
|  **请求方式** | $method  |  |   |   |
|  **输入参数** |  **名称** | **含义**  | **示例**  | **必填** |

STR;
            foreach ($req_list as $item) {
                $not_null = strstr($item, '//') ? '  n' : '`y`';
                $str = ltrim($item, '//');
                if (empty($str)) {
                    continue;
                }
                $index = strpos($str, ':');
                $i = [substr($str, 0, $index), substr($str, $index + 1)];
                if (count($i) > 1) {
                    $text = key_exists($i[0], $attr_list) ? $attr_list[$i[0]] : '&nbsp;';
                    $val = trim($i[1]);
                    $doc .= "|   | `{$i[0]}`  | $text | $val | $not_null | \n";
                }
            }
            $doc .= '|  **输出参数** |  **名称** | **含义**  | **示例**  | **类型**| ';
            if ($res_list) {
                $doc = $this->format($res_list, $attr_list, $doc);
            }
        }
        $doc = urldecode($doc);
        return view('tools.doc', compact('title', 'uri', 'method', 'res', 'req', 'doc', 'attr'));
    }

    function format($res_list, $attr_list, $doc, $prefix = '')
    {
        $this->filterAttr($res_list);
        foreach ($res_list as $key => $val) {
            $inner_doc = null;
            $type = gettype($val);
            if ($type == 'boolean') {
                $val = $val ? 'true' : 'false';
            }
            if (($type == 'array' || $type == 'object') && $key != '_id' && !isset($val['sec']) && !isset($val['usec'])) {
                $val = json_encode($val);
                if (strstr($val, '{') && strstr($val, '}')) {
                    $list = json_decode($val, true);
                    //数组只取第一个
                    if (isset($list[0])) {
                        $list = $list[0];
                    }
                    $pre = substr($key, 0, 1);
                    $this->filterAttr($list);
                    $inner_doc = $this->format($list, $attr_list, '', $pre);
                }
            }
            if ($key == '_id' || (isset($val['sec']) && isset($val['usec']))) {
                $val = json_encode($val);
                $val = json_encode(json_decode($val), JSON_UNESCAPED_UNICODE);
            }
            $text = key_exists($key, $attr_list) ? $attr_list[$key] : '&nbsp;';
            if (isset($inner_doc)) {
                $val = json_decode($val, true);
                $this->filterAttr($val[0]);
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                $this->appendBr($val);
                $doc .= " \n|   | `{$key}`=>`$pre`  | $text | {$val} | {$type} |";
                $doc .= $inner_doc;
            } else {
                $str = "`{$key}`";
                if ($prefix) {
                    $str = "`$prefix`.`{$key}`";
                }
                $this->appendBr($val);
                $doc .= " \n|   | $str  | $text | {$val} | {$type} |";
            }
        }
        return $doc;
    }

    public function filterAttr(&$list)
    {
        $unset_arr = ['__REMOVED__', '__MODIFY_TIME__', '__CREATE_TIME__', 'is_delete'];
        foreach ($unset_arr as $item) {
            unset($list[$item]);
        }
    }

    /**
     * 添加<br/>
     * @param $str
     * @return bool
     */
    function appendBr(&$str)
    {
        if (mb_strlen($str) <= 30) {
            return false;
        }
        while (strstr($str, '},{')) {
            $this->str_insert($str, stripos($str, '},{') + 1, '<br/>');
        }
        while (strstr($str, '","')) {
            $this->str_insert($str, stripos($str, '","') + 1, '<br/>');
        }
    }

    /**
     * 在指定位置添加字符
     * @param $str
     * @param $index
     * @param $is
     */
    function str_insert(&$str, $index, $is)
    {
        $ss = substr($str, 0, $index + 1);
        $ls = substr($str, $index + 1);
        $str = $ss . $is . $ls;
    }

}
