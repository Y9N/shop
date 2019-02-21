<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/wxmedia',WeixinMediaController::class); //微信素材管理


    $router->get('/autosend','WeixinAutoSendController@index'); //微信群发消息
    $router->post('/','WeixinAutoSendController@autosend'); //微信群发消息

    $router->get('/sendmsg','WeixinMediaController@sendMsgView'); //保存永久素材
    $router->post('/sendmsg','WeixinMediaController@sendMsg'); //保存永久素材
});
