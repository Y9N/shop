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
		$this->middleware('auth');
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
		$OrderNumber=CmsOrder::generateOrderSN();
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
		$orderdata=CmsOrder::where('uid',$this->uid)->where('is_del',1)->get()->toArray();
		if(!$orderdata){
			die('无订单数据');
		}else{
			$data=[
				'orderdata'=>$orderdata
			];
			return view('order.order',$data);
		}
	}
	/*
	 * 订单删除*/
	public function orderdel($order_number){
		$orderdata=CmsOrder::where('order_number',$order_number)->where('uid',$this->uid)->first();
		if(!$orderdata){
			die('无订单信息');
		}else{
			$res=CmsOrder::where('order_number',$order_number)->where('uid',$this->uid)->update(['is_del'=>2]);
			if($res){
				echo '取消订单成功';
			}else{
				echo '取消订单失败';
			}
		}
	}



	public function ordertDel(){
		$data=CmsOrder::get()->toArray();
		foreach($data as $k=>$v){
			if($v['is_pay']==2){
				if(time()-$v['add_time']>30){
					CmsOrder::where(['order_id'=>$v['order_id']])->update(['is_del'=>2]);
				}
			}
		}
		echo date('Y-m-d H:i:s')."执行delOrder\n\n";
	}
}