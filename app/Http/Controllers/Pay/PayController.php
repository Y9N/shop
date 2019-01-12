<?php

namespace App\Http\Controllers\Pay;

use App\Model\CmsCart;
use App\Model\CmsGoods;
use App\Model\CmsOrder;
use App\Model\CmsShop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class PayController extends Controller
{
	public function pay(){
		$url='http://yc.tactshan.com/';
		$client=new Client(['base_uri'=>$url,'timeout'=>2.0,]);
		$response=$client->request('GET','/index.php');
		echo $response->getBody();
	}
	public $uid;
	public function __construct()
	{
		$this->middleware(function($request,$next){
			$this->uid=session()->get('u_id');
			return $next($request);
		});
	}
	/**
	 * 订单支付
	 */
	public function orderpay($order_number)
	{
		$order_number=base64_decode($order_number);
		$order_info = CmsOrder::where(['order_number'=>$order_number])->where('uid',$this->uid)->first();
		if(!$order_info){
			die("订单 ".$order_number. "不存在！");
		}
		if($order_info->pay_time > 0){
			die("此订单已被支付，无法再次支付");
		}
		$res=CmsOrder::where(['order_number'=>$order_number])->update(['pay_time'=>time(),'pay_amount'=>rand(1111,9999),'is_pay'=>1]);
		if($res){
			echo '支付成功';
			$pay_amount=CmsOrder::where(['order_number'=>$order_number])->value('pay_amount');
			$integral=$pay_amount*100;
			$old_integral=CmsShop::where('id',$this->uid)->value('integral');
			$new_integral=$integral+$old_integral;
			CmsShop::where('id',$this->uid)->update(['integral'=>$new_integral]);
		}else{
			echo '支付失败';
		}
	}
}