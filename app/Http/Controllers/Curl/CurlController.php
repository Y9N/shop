<?php

namespace App\Http\Controllers\Curl;

use App\Model\CmsCart;
use App\Model\CmsGoods;
use App\Model\UserModel;
use App\Model\UserCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class CurlController extends Controller
{
    public function curl2()
    {
        return view('curl.curl');
    }
}