<?php

namespace App\Http\Controllers\Api;

use App\Model\UserModel;
use App\Model\UserCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use DB;

class IndexController extends Controller
{

	public function index()
	{
		$data=[
			'name'=>$_POST['name'],
			'pwd'=>$_POST['pwd']
		];
		$url="http://yycc.zty77.com/api";
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,true);//文件上传
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data); //文件上传
		curl_setopt($ch,CURLOPT_HEADER,0);//不返回头部信息
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//
		//抓取url传给浏览器
		$rs=curl_exec($ch);
		var_dump($rs);
		die;
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
		$info=UserModel::where('name',$name)->first();
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
			'email'=>$_POST['emall']
		];
		$id=UserModel::insertGetId($data);
		echo json_encode($id);
	}
}