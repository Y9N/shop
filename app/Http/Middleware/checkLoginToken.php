<?php

namespace App\Http\Middleware;

use Closure;

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
        if(!$request->session()->get('u_token')){
            header('Refresh:2;url=/userlogin');
            echo '还没登录 快去登录！！';
            die;
        }
        return $next($request);
    }
}
