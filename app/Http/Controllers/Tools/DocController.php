<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('tools.doc', ['doc' => null]);
        }
        $uri = $request->get('uri');
        $method = $request->get('method');
        $request_str = $request->get('request');
        $response_str = $request->get('response');
        $request_list = explode('//', rtrim($request_str, '{{url}}'));
        $response_list = json_decode($response_str, true);

        $doc = <<<STR
| URI | $uri  |   |   |   | <br/>
| ------------ | ------------ | ------------ | ------------ |  ------------ |<br/>
|  请求方式 | $method  |  |   |   |<br/>
|  输入参数 |  名称 | 含义  | 示例  | 必填  |<br/>
STR;
        foreach ($request_list as $item) {
            if ($item) {
                $i = explode(':', $item);
                if (count($i) > 1) {
                    $doc .= "|   | `{$i[0]}`  | &nbsp;| {$i[1]} | n | <br/>";
                }
            }
        }
        $doc .= '|  输出参数 |  名称 | 含义  | 示例  | 类型 | <br/>';
        if (is_array($response_list)) {
            foreach ($response_list as $key => $val) {
                $type = gettype($val);
                $doc .= "|   | `{$key}`  | &nbsp;| {$val} | {$type} | <br/>";
            }
        }
        if (strlen($doc) > 279) {
            \Session::flash('doc', $doc);
        }
        return redirect()->back()->withInput();
    }

}
