<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinMedia;
use App\Model\WxMsg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class WeixinUserController extends Controller
{
   public function getCode()
   {
       print_r($_GET);echo '<br>';
       $code=$_GET['code'];
       echo 'code:'.$code;
   }
}
