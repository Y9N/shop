<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class checkLoginToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if(!$request->session()->get('u_token')){*/
        if(empty($_COOKIE['token'])){
            header('Refresh:2;url=http://passport.shop.com/login');
            echo '还没登录,马上跳转至登录页面，请稍后。。。';
            die;
        }else{
            $uid=$_COOKIE['uid'];
            $token=Redis::get("str:uid:$uid");
            if($_COOKIE['token']==$token){
                $islogin=1;
                //var_dump($next($request));die;
            }else{
                header('Refresh:2;url=http://passport.shop.com/login');
                echo '用户信息过期，请重新登录';
                die;
            }
        }
        $request->attributes->add(['islogin'=>$islogin]);
        return $next($request);
    }
}
