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
    $router->resource('/autosend',WeixinAutoSendController::class); //微信群发消息
    $router->post('/fasong','WeixinAutoSendController@autosend'); //微信群发消息
});
