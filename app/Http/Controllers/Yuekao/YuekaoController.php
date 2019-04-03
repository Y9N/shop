<?php

namespace App\Http\Controllers\Yuekao;

use App\Model\UserModel;
use App\Model\WeixinMedia;
use App\Model\WxMsg;
use App\Model\Yuekao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class YuekaoController extends Controller
{
   public function index()
   {
       return view('yuekao.reg');
   }
    public function reg(Request $request)
    {
        $name=$request->input('name');
        $number=$request->input('number');
        $file=$_FILES['file']['tmp_name'];
        $yongtu=$request->input('yongtu');
        $userinfo=Yuekao::where('number',$number)->first();
        if($userinfo){
            $id=$userinfo['id'];
            cookie('uid',$id);
            Redis::incr("str:id:$id");
            $reg_num=Yuekao::where('number',$number)->value('reg_num');
            $newnum=$reg_num+1;
            $r_num=Yuekao::where('number',$number)->update(['reg_num'=>$newnum]);
            if($r_num){
                $info=[
                    'id'=>$userinfo['id']
                ];
                return view('yuekao.shenhe',$info);
            }else{
                echo '提交失败，请重试';
            }
        }else{
            $data=[
                'name'=>$name,
                'number'=>$number,
                'file'=>$file,
                'yongtu'=>$yongtu,
                'reg_num'=>1,
                'is_pass'=>1
            ];
            $uid=Yuekao::insertGetId($data);
            cookie('uid',$uid);
            Redis::incr("str:id:$uid");
            if($uid){
                $info2=[
                    'id'=>$uid
                ];
                return view('yuekao.shenhe',$info2);
            }else{
                echo '提交失败，请重试';
            }
        }
    }
    public function reg_do(Request $request)
    {
        $id=$request->input('id');
        $pass=Yuekao::where('id',$id)->value('is_pass');
        if($pass==1){
            $data=[
                'error'=>'500',
                'msg'=>'wait!'
            ];
            echo json_encode($data);die;
        }elseif($pass==2){
            $privatekey = openssl_pkey_get_private(file_get_contents('./key/id_rsa.key'));
            $as=Redis::get("app_key:id:$id");
            $sensitiveData = '';
            //使用私钥解密
            openssl_private_decrypt(base64_decode($as), $sensitiveData, $privatekey);
            //var_dump($sensitiveData);die; // 应该跟$data一致
            $sensitiveData=json_decode($sensitiveData);
            //var_dump($sensitiveData);die;
            $data=[
                'error'=>'0',
                'msg'=>'pass',
                'app_key'=>$sensitiveData->app_key,
                'app_secret'=>$sensitiveData->app_secret
            ];
            echo json_encode($data);
        }elseif($pass==3){
            $nopassmsg=Yuekao::where('id',$id)->value('msg');
            $data=[
                'error'=>'400',
                'msg'=>'no pass',
                'nopass'=>$nopassmsg
            ];
            echo json_encode($data);
        }
    }
}
