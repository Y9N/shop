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
			$array=CmsGoods::all()->toArray();
			return view('goods.goods',['array'=>$array]);
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

}