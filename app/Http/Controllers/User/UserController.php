<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\CmsShop;

class UserController extends Controller
{
    //

	public function user($uid)
	{
		echo $uid;
	}

	public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = CmsShop::insertGetId($data);
		var_dump($id);
	}

	/** 注册*/
	public function reg(){
		return view('users.reg');
	}
	public function doReg(Request $request){
		$pwd = password_hash($request->input('password'),PASSWORD_BCRYPT);
		$name=$request->input('name');
		$shu = CmsShop::where('name',$name)->first();
		if($shu){
			exit('用户名已存在');
		}
		$data = [
			'name' =>$name,
			'password' => $pwd,
			'age' => $request->input('age'),
			'email' => $request->input('email')
		];
		if(empty($data['name'])){
			exit('用户名必填');
		}
		if(empty($request->input('password'))){
			exit('密码必填');
		}
		if($request->input('password')!=$request->input('repassword')){
			exit('两次密码不一致');
		}
		if(empty($data['age'])){
			exit('年龄必填');
		}
		if(empty($data['email'])){
			exit('邮箱必填');
		}
		$id = CmsShop::insert($data);
		//var_dump($id);
		if($id){
			echo '注册成功';
			setcookie('uid',$id,time()+86400,'/','larvel.com',false,true);
			header("refresh:1;'/userlist'");
		}else{
			echo '注册失败';
		}
	}

    /** 登录*/
    public function login(){
        return view('users.login');
    }
	public function doLogin(Request $request){
		//echo __METHOD__;
        $name = $request->input('name');
        $pwd = $request->input('password');
        $where = [
            'name' => $name,
        ];
        $res = CmsShop::where($where)->first();
		$mysqlpwd=$res['password'];
		$uid=$res['id'];
		$RES=password_verify($pwd,$mysqlpwd);
		if(password_verify($pwd,$mysqlpwd)){
			echo '登录成功';
			$token = substr(md5(time().mt_rand(1,99999)),10,10);
			setcookie('uid',$uid,time()+86400,'/','larvel.com',false,true);
			setcookie('token',$token,time()+86400,'/userlist','',false,true);
			header("refresh:2;'/userlist'");
		}else{
			echo '登录失败';
		}
	}
	public function list(){
		if(empty($_COOKIE['uid'])){
			exit('请先登录呀！！！！');
		}else{
			$id=$_COOKIE['uid'];
			$data=CmsShop::where('id',$id)->first();
			return view('users.list',$data);
		}

	}
}
