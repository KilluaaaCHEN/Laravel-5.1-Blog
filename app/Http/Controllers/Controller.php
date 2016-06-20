<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function flashState($is_success, $title, $desc = null)
    {
        \Session::flash('msg_state', $is_success);
        \Session::flash('msg_title', $title);
        \Session::flash('msg_desc', $desc);
    }
}
