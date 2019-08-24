<?php

namespace App\Http\Controllers\Tools;

use App\Helper\Common;
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
        $doc_method = $method;
        $req = $request->get('request');
        $res = $request->get('response');
        $attr = $request->get('attr');
        $doc = '';
        if ($request->getMethod() == 'POST') {
            $uri = ltrim($uri, '{{domain}}');
            $count = substr_count(PHP_EOL, $req);
            $res_list = json_decode($res, true);
            $req_demo = '';
            if ($count) {
                $req_list = explode(PHP_EOL, $req);
            } else {
                $req_list = json_decode($req, true);
                $req_demo = json_encode($req_list, JSON_UNESCAPED_UNICODE);
                $req_demo=<<<STR
> 请求示例
```
$req_demo
```
STR;

            }
            //查询字典
            $attr_list = Dict::pluck('val', 'key')->toArray();
//            //多个数组合并属性
//            $attr_arr = explode('}', $attr);
//            $attr_dic = [];
//            foreach ($attr_arr as $item) {
//                if (!empty($item)) {
//                    $attr_dic = array_merge($attr_dic, json_decode($item . '}', true));
//                }
//            }
            $attr_dic = json_decode($attr, true);
            if ($attr_dic) {
                $attr_list = array_merge($attr_list, $attr_dic);
            }

            //数组格式初始化
            if ($count) {
                $content_type = 'application/x-www-form-urlencoded';
                $req_data = [];
                foreach ($req_list as $item) {
                    if (empty($item)) {
                        continue;
                    }
                    $index = strpos($item, ':');
                    $key = substr($item, 0, $index);
                    $val = rtrim(substr($item, $index + 1), "\r");
                    $this->appendBr($val);
                    if (isset($req_data[$key])) {
                        $req_data[$key][] = $val;
                    } elseif (strpos($key, '[]') !== false) {
                        $req_data[$key][] = $val;
                    } else {
                        $req_data[$key] = $val;
                    }
                }
            } else {
                $content_type = 'application/json';
                foreach ($req_list as $key => $item) {
                    $req_data[$key] = $item;
                }
            }

            //非必填的放在后面
            foreach ($req_data as $key => $val) {
                if (strstr($key, '//')) {
                    unset($req_data[$key]);
                    $req_data[$key] = $val;
                }
            }
            $doc = <<<STR
###  $title 
| URI | $uri  |   |   |   | 
| :----: | ------------ | ------------ | ------------ |  :----: |
|  **请求方式** | $method  | **Content-Type** | $content_type  |   |
|  **输入参数** |  **名称** | **含义**  | **示例**  | **必填** |

STR;

            //格式化请求参数
            if ($count) {
                foreach ($req_data as $key => $val) {
                    $not_null = strstr($key, '//') ? '  n' : '`y`';
                    $key = ltrim($key, '//');
                    $key = rtrim($key, '[]');
                    if (empty($key)) {
                        continue;
                    }
                    $key = strtolower($key);
                    if (key_exists($key, $attr_list)) {
                        $text = $attr_list[$key];
                    } else {
                        $text = Common::bdTranslateAction($key, $attr_list);
                    }
                    if (is_array($val)) {
                        $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                        $text .= ',数组';
                    }
                    $doc .= "|   | `{$key}`  | $text | $val | $not_null | \n";
                }
            } else {
                foreach ($req_data as $key => $val) {
                    $key = strtolower($key);
                    if (key_exists($key, $attr_list)) {
                        $text = $attr_list[$key];
                    } else {
                        $text = Common::bdTranslateAction($key, $attr_list);
                    }
                    if (is_array($val)) {
                        $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                        $text .= '数组';
                    }
                    $doc .= "|   | `{$key}`  | $text | $val | `y` | \n";
                }
            }
            $doc .= '|  **输出参数** |  **名称** | **含义**  | **示例**  | **类型**| ';

            //格式化响应数据
            if ($res) {
                $format_res = $res_list;
                if ($res_list && isset($res_list['result'])) {
                    $format_res = $res_list['result'];
                    if (!isset($format_res['datas']) && is_array($format_res) && isset($format_res[0])) {
                        $format_res = $format_res[0];
                    }
                }
                $doc = $this->format($format_res, $attr_list, $doc);
                //响应示例 只取第一条数据
//                if (isset($res_list['result']['datas'])) {
//                    $res_list['result']['datas'] = [$res_list['result']['datas'][0]];
//                } elseif (isset($res_list['result']) && is_array($res_list['result']) && isset($res_list['result'][0])) {
//                    $res_list['result'] = [$res_list['result'][0]];
//                }
                $json_demo = json_encode($res_list, JSON_UNESCAPED_UNICODE);
                $doc .= <<<STR
            
$req_demo
> 响应示例
```
$json_demo
```
STR;
            }
        }
        $doc = urldecode($doc);
        return view('tools.doc', compact('title', 'uri', 'method', 'res', 'req', 'doc', 'attr'));
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
        $str = str_replace('"0":null', '', $str);
        $str_arr = explode(',', $str);
        $str = '';
        if (count($str_arr) > 1) {
            foreach ($str_arr as $item) {
                if ($item != '}') {
                    $str .= $item . ',<br/>';
                } else {
                    $str = rtrim($str, ',<br/>');
                    $str .= $item;
                }
            }
        } else {
            $str = $str_arr[0];
        }
        $str = rtrim($str, ',<br/>');
        $str = str_replace('<br/><br/>', '<br/>', $str);
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

    function format($res_list, &$attr_list, $doc, $prefix = '')
    {
        $this->filterAttr($res_list);
        //数组牌组,对象放最后
        $after = [];
        if (empty($res_list)) {
            return $doc;
        }
        foreach ($res_list as $i => $item) {
            if (is_array($item) && !isset($item['sec']) && !isset($item['usec']) && $i != '_id') {
                $after[$i] = $item;
                unset($res_list[$i]);
            }
        }
        foreach ($after as $i => $item) {
            $res_list[$i] = $item;
        }
        foreach ($res_list as $key => $val) {
            $inner_doc = null;
            $type = gettype($val);
            if ($type == 'boolean') {
                $val = $val ? 'true' : 'false';
            }
            if (($type == 'array' || $type == 'object') && $key != '_id' && !isset($val['sec']) && !isset($val['usec'])) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);

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
            if (isset($val['sec']) && isset($val['usec'])) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
            }
//            $text = key_exists($key, $attr_list) ? $attr_list[$key] : '&nbsp;';
            $s_key = strtolower($key);
            if (array_key_exists($s_key, $attr_list)) {
                $text = $attr_list[$s_key];
            } else {
                $text = Common::bdTranslateAction($key, $attr_list);
            }
            if (isset($inner_doc)) {
                //取消内嵌示例
                /*$val = json_decode($val, true);
                $this->filterAttr($val[0]);
                if (count($val[0]) > 1) {
                    $val = [$val[0]];
                }
                $this->filterAttr($val);
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                $this->appendBr($val);
                $val = str_replace('\/', '/', $val);*/
                $doc .= " \n|   | `{$key}`=>`$pre`  | {$text}信息 |  | {$type} |";
                $doc .= $inner_doc;
            } else {
                $str = "`{$key}`";
                if ($prefix) {
                    $str = "`$prefix`.`{$key}`";
                }
                if (is_array($val)) {
                    $val = json_encode($val, JSON_UNESCAPED_UNICODE);
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
            if (isset($list[$item])) {
                unset($list[$item]);
            }
        }
        if (is_array($list)) {

            foreach ($list as &$item) {
                if (is_string($item) && mb_strlen($item) > 50 && strpos($item, 'http://') !== false) {
                    $item = substr($item, 0, 50);
                }
            }
        }
    }

}
