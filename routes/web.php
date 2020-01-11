<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*后台*/
Route::prefix('admin/')->group(function(){
    Route::get('login','Admin\LoginController@login');//登陆视图
    Route::any('logindo','Admin\LoginController@logindo');//执行登录
    
    Route::any('index','Admin\IndexController@index');//首页
    Route::any('index_v1','Admin\IndexController@index_v1');//首页
    Route::any('shou','Admin\IndexController@shou');//首页温度
    Route::any('getshou','Admin\IndexController@getshou');//首页温度

    Route::any('media_show','Admin\MediaController@show');//素材管理
    Route::any('media_add','Admin\MediaController@add');//素材管理
    Route::any('add_do','Admin\MediaController@add_do');//执行添加
    
    Route::any('create','Admin\NewsController@create');//新闻添加
    Route::any('store','Admin\NewsController@store');//新闻执行添加
    Route::any('list','Admin\NewsController@list');//新闻列表展示
    Route::any('delete/{new_id}','Admin\NewsController@delete');//新闻的删除
    Route::any('update/{new_id}','Admin\NewsController@update');//新闻的修改视图
    Route::any('updatedo/{new_id}','Admin\NewsController@updatedo');//新闻的执行修改

    Route::any('show','Admin\ChannelController@show');//渠道添加
    Route::any('add','Admin\ChannelController@add');//渠道执行添加
    Route::any('lists','Admin\ChannelController@lists');//渠道执行添加
    Route::any('chart','Admin\ChannelController@chart');//渠道图表
    
    Route::any('createMenu','Admin\WeixinController@createMenu');//自定义菜单栏
        
    Route::any('sendAllByOpenId','Admin\WeixinController@sendAllByOpenId');//项目自动上线
    

    Route::any('uploadMedia','Admin\MediaController@uploadMedia');//测试
    Route::any('createdo','Admin\MediaController@createdo');//测试2
});

/*微信*/
Route::prefix('weixin/')->group(function(){
    /*更改配置*/
    Route::any('index','Admin\WeixinController@index');//微信
    Route::any('wx','Admin\NewsController@wx');//微信
});

Route::any('/gitpull','Git\IndexController@index');//项目自动上线
