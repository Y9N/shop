<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/test/*',
        '/pay/alipay/notify',
        '/weixin/valid1',
        '/weixin/valid',
        '/admin/autosend',
        '/admin/touser',
        '/admin/touser/msg',
        '/weixin/pay/notice',
        '/weixin/pay/ifsuccess',
        '/admin/sign',
        '/curl2',
        '/encrypt',
        '/sign',
        '/api',
        '/api/*',
        '/*'
    ];
}
