<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 15/11/7
 * Time: 12:33
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Input;
use Redirect;
use Illuminate\Http\Request;
use Storage;
use Validator;
use Illuminate\Http\File;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

class AdminController extends Controller
{

    public function index()
    {
        return view('backend.index');
    }

    public function login()
    {
        if (\Auth::check()) {
            return redirect()->route('admin.index');
        }
        return view('backend.admin.login');
    }

    public function postLogin(\Request $request)
    {
        $email = $request::get("email");
        $pwd = $request::get('password');
        $remember = $request::get('remember', false);
        // 验证参数
        $validator = Validator::make(\Request::all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误',
            'password.required' => '密码不能为空',
        ]);
        if ($validator->fails()) {
            return Redirect::route('admin.login')->withErrors($validator)->withInput(\Request::all());
        }
        if (\Auth::attempt(['email' => $email, 'password' => $pwd], $remember)) {
            return redirect()->route('admin.index');
        } else {
            $validator->getMessageBag()->add("err", "账号或者密码错误");
            return Redirect::route('admin.login')->withErrors($validator)->withInput(\Request::except('password'));
        }
    }

    public function logout()
    {
        \Auth::logout();
        return redirect()->route('admin.login');
    }

    public function upload(Request $request)
    {
        $upManager = new UploadManager();
        $auth = new Auth(env('QINIU_AK'), env('QINIU_SK'));
        $token = $auth->uploadToken('killua');
        $file = $request->file('editormd-image-file');
        $file_path = $file->getPathname();
        $key = 'blog/' . uniqid();
        $rst = ['success' => 0, 'message' => '图片上传失败,20M以内'];
        if (file_exists($file_path)) {
            list($ret, $error) = $upManager->putFile($token, $key, $file_path, null, $file->getMimeType());
            if (is_null($error)) {
                $rst = ['success' => 1, 'url' => 'http://images.larry666.com/' . $key];
            }
        }
        return json_encode($rst);

    }
}