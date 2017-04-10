<?php


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

