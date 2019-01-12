<?php

namespace App\Http\Controllers\Cart;

use App\Model\CmsCart;
use App\Model\CmsGoods;
use App\Model\UserModel;
use App\Model\UserCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class IndexController extends Controller
{
	public $uid;
	public function __construct()
	{
		$this->middleware(function($request,$next){
			$this->uid=session()->get('u_id');
			return $next($request);
		});
	}

	//
	public function index(Request $request)
	{
		$cart_session=session()->get('cart_session');
		if(empty($cart_session)){
			echo '购物车是空的';
		}else{
			$array=[];
			foreach($cart_session as $k=>$v){
				$data=CmsGoods::where('goods_id',$v)->first()->toArray();
				$array[]=$data;
			}
			return view('cart.cart',['array'=>$array]);
		}
	}
	public function index2(Request $request)
	{
		$u_id=session()->get('u_id');
		$array=CmsCart::where(['u_id'=>$u_id])->get();
		$arr=[];
		foreach($array as $k=>$v){
			$data=CmsGoods::where('goods_id',$v['goods_id'])->first()->toArray();
			$data['buy_num']=$v['buy_num'];
			$data['add_time']=$v['add_time'];
			$arr[]=$data;
		}
		//print_r($array);exit;
		//print_r($array);die;
		return view('cart.cart',['array'=>$arr]);
	}

	/**
	 * 添加商品
	 */
	public function add($goods_id)
	{
		//创建session
		$cart_session=session()->get('cart_session');
		//判断session中是否存在该id
		if(!empty($cart_session)){
			if(in_array($goods_id,$cart_session)){
				echo '已存在购物车中';
				exit;
			}
		}
		//存session值
		session()->push('cart_session',$goods_id);
		//根据id查询库存
		$where=['goods_id'=>$goods_id];
		$score=CmsGoods::where($where)->value('score');
		if($score<=0){
			die('库存不足');
		}
		$res=CmsGoods::where($where)->decrement('score');
		if($res){
			echo '存入成功';
			header('Refresh:2;url=/cart');
		}else{
			echo '存入失败';
		}
	}

	public function add2(Request $request)
	{
		$goods_id=$request->input('goods_id');
		$buy_num=$request->input('buy_num');
		$where=['goods_id'=>$goods_id];
		$score=CmsGoods::where($where)->value('score');
		if($score<=0||$buy_num>$score){
			$response = [
					'errno' => 5002,
					'msg'   => '库存不足'
			];
			return $response;
		}
		//检测是否重复购物
		$cart_goods = CmsCart::where(['u_id'=>$this->uid])->get()->toArray();
		if($cart_goods){
			$goods_id_arr = array_column($cart_goods,'goods_id');
			if(in_array($goods_id,$goods_id_arr)){
				 die('商品已在购物车中，请勿重复添加');
			}
		}
		$data=[
			'goods_id'=>$goods_id,
			'buy_num'=>$buy_num,
			'u_id'=>session()->get('u_id'),
			'session_token'=>session()->get('u_token'),
			'add_time'=>time()
		];
		$res=CmsCart::insert($data);
		if($res){

			echo '加入购物车成功';
			header("refresh:2;url=/cart2");
		}else{
			echo '加入购物车失败';
			header("refresh:2;url=/cart/godoslist/$goods_id");
		}
	}
	/**
	 * 删除商品
	 */
	public function del($goods_id)
	{
		$cart_session = session()->get('cart_session');
		if(in_array($goods_id,$cart_session)){
			foreach($cart_session as $k=>$v){
				if($goods_id == $v){
					session()->pull('cart_session.'.$k);
					echo '删除成功'.'<br>'."id:$v";
					header('Refresh:2;url=/cart');
				}
			}
		}else{
			echo '商品不在购物车不能删除';
		}
	}
	public function del2($goods_id)
	{
		//echo $goods_id;
		$res=CmsCart::where(['u_id'=>session()->get('u_id'),'goods_id'=>$goods_id])->delete();
		if($res){
			header('refresh:0;url=/cart2');
		}else{
			$response = [
					'errno' => 5002,
					'msg'   => '删除失败'
			];
			return $response;
		}
	}
}