<?php

namespace App\Http\Controllers\Tools;

use App\Helper\Curl;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QrCodeController extends Controller
{
    public function generate(Request $request)
    {
        $query = $request->get('q');
        $data = [
            't' => $query,
            'dt' => 'text',
            'f' => '#000',
            'b' => '#FFFFFF',
            'pt' => '#000000',
            'inpt' => '#000000',
            's' => 1,
            'lap' => 0,
            'eap' => 0,
            'level' => 'L'

        ];
        $rst = json_decode(Curl::post('http://www.wwei.cn/Qrcode/create.html', $data));
        return "<div style='width:300px;margin: 100px auto;'><img style='width:300px' src='http://www.wwei.cn/$rst->img' /></div>";
    }


}
