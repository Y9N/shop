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
//    echo date('Y-m-d H:i:s');
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/test','User\UserController@test');
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');



// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>40300]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


//Route::match(['get','post'],'/test/abc','Test\TestController@abc');
Route::any('/test/abc','Test\TestController@abc');

/*视图层的test*/
Route::get('/view/child','Test\TestController@viewChild');

/** 注册*/
Route::get('/userreg','User\UserController@reg');
Route::post('/userreg','User\UserController@doReg');
/** 登录*/
Route::get('/userlogin','User\UserController@login');
Route::post('/userlogin','User\UserController@doLogin');
/*展示页面*/
Route::get('/goodslist','Goods\GoodsController@goods');
/*中间件测试*/
Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');
Route::get('/test/check_uid','Test\TestController@checkUid')->middleware('check.uid');
//购物车
//Route::get('/cart','Cart\IndexController@index')->middleware('check.uid');
Route::get('/cart','Cart\IndexController@index')->middleware('check.login.token');
Route::get('/cart2','Cart\IndexController@index2')->middleware('check.login.token');
/*添加购物车*/
Route::get('/cart/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');
Route::post('/cart/add2','Cart\IndexController@add2')->middleware('check.login.token');
/*删除购物车*/
Route::get('/cart/del/{goods_id}','Cart\IndexController@del')->middleware('check.login.token');
Route::get('/cart/del2/{goods_id}','Cart\IndexController@del2')->middleware('check.login.token');
/*查看商品详情*/
Route::get('/cart/goodslist/{goods_id}','Goods\GoodsController@goodslist');
/*生成订单*/
Route::post('/order','Order\OrderController@createorder')->middleware('check.login.token');
/*订单查看*/
Route::get('/orderlist','Order\OrderController@orderlist')->middleware('check.login.token');
/*取消订单*/
Route::get('/orderdel/{order_number}','Order\OrderController@orderdel')->middleware('check.login.token');
/*订单支付*/
Route::get('/orderpay/{order_number}','Pay\PayController@orderpay')->middleware('check.login.token');
/*用户详情*/
Route::get('/userlist','User\UserController@userlist')->middleware('check.login.token');
/*用户退出*/
Route::get('/userquit','User\UserController@userquit');
Route::get('/pay','Pay\PayController@pay');

Route::get('/pay/alipay/test/{order_number}','Pay\AlipayController@pay');
Route::post('/pay/alipay/notify','Pay\AlipayController@notify');
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 异步通知回调
Route::get('/pay/alipay/return','Pay\AlipayController@aliReturn');        //支付宝支付 同步通知回调
/*删除过期订单*/
Route::get('/pay/alipay/ordertDel','Pay\AlipayController@ordertDel');