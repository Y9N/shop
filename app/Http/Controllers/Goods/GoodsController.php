<?php

namespace App\Http\Controllers\Goods;

use App\Model\CmsGoods;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
//use DB;
use Illuminate\Filesystem\Cache;

class GoodsController extends Controller
{
	//全部商品
	public function goods(){
			$array=CmsGoods::all()->toArray();
			return view('goods.goods',['data'=>['data'=>$array]]);
		}
	//条件商品
	public function goodspage(Request $request){
		$goods_name=$request->goods_name;
		$array=CmsGoods::where('goods_name','like',"%$goods_name%")->paginate(3)->toArray();
		//$array=$array->paginate();
		//print_r($array);die;
		return view('goods.goods',['data'=>$array]);
	}



	public function goodslist($goods_id)
	{
		$data=CmsGoods::where('goods_id',$goods_id)->first()->toArray();
		return view('goods.goodslist',$data);
	}

	public function uploadPDF()
	{
		return view('goods.upload');
	}

	public function PDF(Request $request)
	{
		$file=$request->file('pdf');
		$ext=$file->extension();
		if($ext!='pdf')
		{
			die('请上传pdf格式文件');
		}
		$name=$file->storeAs(date('Ymd'),str_random(5).'.pdf');
		if($name)
		{
			echo '上传成功';
		}
	}


	public function redis(){
		$key = '3333';
		if (Cache::has($key)){                //首先查寻cache如果找到
			$values = Cache::get($key);    //直接读取cache
			dd($values);
		}else{                                   //如果cache里面没有
			$value = '4444';
			Cache::put($key,$value,500);

		}
		dd(Cache::get($key));
	}
}