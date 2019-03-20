<?php

namespace App\Http\Controllers\Api;

use App\Model\CmsCart;
use App\Model\CmsGoods;
use App\Model\UserModel;
use App\Model\UserCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class IndexController extends Controller
{
	public function index()
	{
		$name=$_POST['name'];
		$pwd=md5($_POST['pwd']);
		$info=UserModel::where('name',$name)->get();
		print_r($info);die;
		if(!$info){
			$code=[
				'error'=>'10000',
				'msg'=>'Wrong account or password!'
			];
			echo json_encode($code);die;
		}else{
			if($pwd!=$info['password']){
				$code=[
						'error'=>'10000',
						'msg'=>'Wrong account or password!'
				];
				echo json_encode($code);die;
			}else{
				$code=[
					'error'=>'0',
					'msg'=>'ok'
				];
				echo json_encode($code);
			}
		}
	}
}