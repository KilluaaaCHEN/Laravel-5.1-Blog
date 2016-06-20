<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 15/11/3
 * Time: 16:29
 */
namespace App\Http\Controllers;

use App\Models\Post;
use Request;

class IndexController extends Controller
{

    public function play()
    {
        $url=Request::get('url');
        $this->layout=false;
        return view('index.play',compact('url'));
    }

}