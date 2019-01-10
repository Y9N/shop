<?php

namespace App\Http\Controllers\Order;

use App\Model\CmsCart;
use App\Model\CmsGoods;
use App\Model\CmsOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class OrderController extends Controller
{
	public $uid;
	public function __construct()
	{
		$this->middleware(function($request,$next){
			$this->uid=session()->get('u_id');
			return $next($request);
		});
	}
	/*
	 * 生成订单号
	 * */
	public function createorder()
	{
		$cart=CmsCart::where('u_id',$this->uid)->get()->toArray();
		if(empty($cart)){
			exit('购物车无数据');
		}
		$order_amount=0;
		foreach($cart as $k=>$v){
			$goods_info = CmsGoods::where(['goods_id'=>$v['goods_id']])->first()->toArray();
			$goods_info['buy_num'] = $v['buy_num'];
			$list[] = $goods_info;
			$order_amount+=$goods_info['goods_price']*$v['buy_num'];
		}
		//生成订单号
		$OrderNumber=CmsOrder::GenerateOrderNumber();
		//echo $OrderNumber;
		$data=[
			'order_number'=>$OrderNumber,
			'uid'=>$this->uid,
			'add_time'=>time(),
			'order_amount'=>$order_amount,
		];
		$oid = CmsOrder::insertGetId($data);
		if(!$oid){
			exit('生成订单失败');
		}else{
			echo '下单成功,订单号：'.$OrderNumber ;
			//清空购物车
			CmsCart::where(['u_id'=>$this->uid])->delete();
			header('refresh:1;url=/orderlist');
		}


	}
	/*
	 * 订单展示
	 *
	 * */
	public  function orderlist(){
		$orderdata=CmsOrder::where('uid',$this->uid)->get()->toArray();
		if(!$orderdata){
			die('无订单数据');
		}else{
			$data=[
				'orderdata'=>$orderdata
			];
			return view('order.order',$data);
		}
	}

}