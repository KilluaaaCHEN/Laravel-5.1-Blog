<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

        $doc = '';
        if ($request->getMethod() == 'POST') {
            $uri = ltrim($uri, '{{domain}}');
            $req_list = explode('//', $req);
            $res_list = json_decode($res, true);

            $req_dic = [
                'page_size' => '页码;每页大小,默认10;',
                'page_index' => '页索引;从0开始,默认0'
            ];
            $res_dic = [
                'open_id' => '用户唯一标示'
            ];

            $doc = <<<STR
### 1.0 $title 
| URI | $uri  |   |   |   | 
| ------------ | ------------ | ------------ | ------------ |  ------------ |
|  请求方式 | $method  |  |   |   |
|  输入参数 |  名称 | 含义  | 示例  | 必填 |

STR;
            foreach ($req_list as $item) {
                $i = explode(':', $item);
                if (count($i) > 1) {
                    $text = key_exists($i[0], $req_dic) ? $req_dic[$i[0]] : '&nbsp;';
                    $val = trim($i[1]);
                    $doc .= "|   | `{$i[0]}`  | $text | $val | n | \n";
                }
            }
            $doc .= '|  输出参数 |  名称 | 含义  | 示例  | 类型 | ';
            if ($res_list) {
                foreach ($res_list as $key => $val) {
                    $val = trim($val);
                    $type = gettype($val);
                    if ($type == 'boolean') {
                        $val = $val ? 'true' : 'false';
                    }
                    $text = key_exists($key, $res_dic) ? $res_dic[$key] : '&nbsp;';
                    $doc .= "|   | `{$key}`  | $text | {$val} | {$type} | \n";
                }
            }
        }
        return view('tools.doc', compact('title', 'uri', 'method', 'res', 'req', 'doc'));
    }

}
