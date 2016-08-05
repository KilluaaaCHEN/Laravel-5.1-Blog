<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TimeStampController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $d_ts = time();
        $d_time = date('Y-m-d H:i:s', $d_ts);
        $time = '';
        $ts = '';
        if ($q) {
            if (is_numeric($q)) {
                $ts = $q;
                $time = date('Y-m-d H:i:s', $ts);
            } else {
                $time = $q;
                $ts = strtotime($time);
            }
        }

        return view('tools.time_stamp', compact('ts', 'time', 'd_ts', 'd_time'));
    }
}
