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


Route::get('test', function () {
    error_reporting(E_ALL & ~E_NOTICE);
    $data = ['name' => 'Killua', 'age' => 24];
    $a = 0;
    $b = 10 / $a;
    var_dump($data['name']);
    var_dump($data['a']);
});
