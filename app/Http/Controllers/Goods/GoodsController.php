<?php

namespace App\Http\Controllers\Goods;

use App\Model\CmsGoods;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class GoodsController extends Controller
{
	public function goods(){
			$id=$_COOKIE['uid'];
			$array=CmsGoods::all()->toArray();
			return view('goods.goods',['array'=>$array]);
		}
	public function goodslist($goods_id)
	{
		$data=CmsGoods::where('goods_id',$goods_id)->first()->toArray();
		return view('goods.goodslist',$data);
	}

}