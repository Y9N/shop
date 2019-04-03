<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Yuekao
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
        //$id=cookie('uid');
        $num=Redis::get("str:id:3");
        //echo $id;die;
        if($num>=10){
            echo '次数上限';die;
        }
        return $next($request);
    }
}
