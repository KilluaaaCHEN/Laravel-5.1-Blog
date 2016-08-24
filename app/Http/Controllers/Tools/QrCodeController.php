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
        $text = $request->get('q');
        return view('tools.qr', compact('text'));
    }


}
