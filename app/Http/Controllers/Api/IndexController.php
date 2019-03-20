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
		$info=UserModel::where('name',$name)->first()->toArray();
		//print_r($info);die;
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
				$token = substr(md5(time().mt_rand(1,99999)),10,10);
				$code=[
					'error'=>'0',
					'msg'=>'ok'
				];
				echo json_encode($code);
			}
		}
	}
	public function reg()
	{
		$name=$_POST['name'];
		$pwd=md5($_POST['pwd']);
		$info=UserModel::where('name',$name)->first()->toArray();
		//print_r($info);die;
		if($info){
			$code=[
					'error'=>'50001',
					'msg'=>'This account has been registered, please login directly!'
			];
			echo json_encode($code);die;
		}
		$data=[
			'name'=>$_POST['name'],
			'password'=>$_POST['pwd'],
			'emall'=>$_POST['emall']
		];
		$id=UserModel::insertGetId($data);
		echo json_encode($id);
	}
}