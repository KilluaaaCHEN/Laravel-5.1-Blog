<?php
/**
 * Created by PhpStorm.
 * User: Killua Chen
 * Date: 17/4/12
 * Time: 11:44
 */


Route::any('callback', ['as' => 'wechat_callback', 'uses' => 'Wechat\IndexController@callback']);
Route::get('menu', 'Wechat\IndexController@menu');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        dd($user);
    });
});