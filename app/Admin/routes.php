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
    $router->resource('/weixinpm',WeixinPmController::class); //微信素材管理


    $router->get('/autosend','WeixinAutoSendController@index'); //微信群发消息
    $router->post('/fasong','WeixinAutoSendController@autosend'); //微信群发消息

    $router->get('/sendmsg','WeixinMediaController@sendMsgView'); //保存永久素材
    $router->post('/','WeixinMediaController@sendMsg'); //保存永久素材

    $router->get('/touser','WeixinController@touserview'); //与用户联系
    $router->post('/touser','WeixinController@touser'); //与用户联系
    $router->get('/usermsg','WeixinController@usermsg'); //更新数据



    $router->get('/redisuser','WeixinController@redisuser'); //redis获取用户信息列表
    $router->get('/userinfo','WeixinController@userinfo'); //redis获取用户信息列表
});
