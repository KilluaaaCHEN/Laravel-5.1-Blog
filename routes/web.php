<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'PostController@index']);
Route::get('/tags/{tag}', ['as' => 'search.tag', 'uses' => 'PostController@searchTag']);
Route::get('/view/{post_id}', ['as' => 'post.view', 'uses' => 'PostController@view']);
//后台
Route::group(['namespace' => 'Backend', 'prefix' => 'admin'], function () {
    //需要登录的Route
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', ['as' => 'admin.index', 'uses' => 'AdminController@index']);
        Route::post('upload', ['as' => 'admin.upload', 'uses' => 'AdminController@upload']);
        Route::resource('post', 'PostController', ['except' => ['update', 'destroy']]);
        Route::get('post/delete/{post_id}', ['as' => 'admin.post.delete', 'uses' => 'PostController@delete']);
        Route::resource('links', 'LinksController', ['except' => ['update', 'destroy']]);
        Route::get('links/delete/{id}', ['as' => 'admin.links.delete', 'uses' => 'LinksController@delete']);
        Route::resource('dict', 'DictController', ['except' => ['update', 'destroy']]);
        Route::get('dict/delete/{id}', ['as' => 'admin.dict.delete', 'uses' => 'DictController@delete']);
        Route::get('logout', ['as' => 'admin.logout', 'uses' => 'AdminController@logout']);
    });
    Route::get('login', ['as' => 'admin.login', 'uses' => 'AdminController@login']);
    Route::post('login', ['as' => 'admin.login', 'uses' => 'AdminController@postLogin']);
});

Route::get('/time-stamp', ['as' => 'ts', 'uses' => 'Tools\TimeStampController@index']);
Route::get('/qr-code', ['as' => 'qr_code', 'uses' => 'Tools\QrCodeController@generate']);
Route::any('/generate/doc', ['as' => 'generate_doc', 'uses' => 'Tools\DocController@generate']);
Route::any('/generate/code', ['as' => 'generate_code', 'uses' => 'Tools\CodeController@generate']);

Route::any('hooks', function () {
    $secret = env('WEBHOOKS_SECRET');
    $path = env('WEBHOOKS_PATH');
    $signature = $_SERVER['X-Hub-Signature'];
    if ($signature) {
        $postdata = file_get_contents("php://input");
        $hash = "sha1=" . hash_hmac('sha1', $postdata, $secret);
        if (strcmp($signature, $hash) == 0) {
            echo shell_exec("cd {$path} && /usr/bin/git reset --hard origin/master && /usr/bin/git clean -f && /usr/bin/git pull 2>&1");
            exit();
        }else{
            var_dump($secret,$path,$signature,$hash);
        }
    }
    http_response_code(404);
});
