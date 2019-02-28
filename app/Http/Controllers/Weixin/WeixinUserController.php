<?php

namespace App\Http\Controllers\Weixin;

use App\Model\UserModel;
use App\Model\WeixinMedia;
use App\Model\WxMsg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class WeixinUserController extends Controller
{
   public function getCode()
   {
       print_r($_GET);echo '<br>';
       $code=$_GET['code'];
       echo 'code:'.$code;
       //2 用code换取access_token 请求接口
       $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
       $token_json = file_get_contents($token_url);
       $token_arr = json_decode($token_json,true);
       echo '<hr>';
       echo '<pre>';print_r($token_arr);echo '</pre>';

       $access_token = $token_arr['access_token'];
       $openid = $token_arr['openid'];

       // 3 携带token  获取用户信息
       $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
       $user_json = file_get_contents($user_info_url);

       $user_arr = json_decode($user_json,true);
       echo '<hr>';
       echo '<pre>';print_r($user_arr);echo '</pre>';
       $rs=$this->dateaseuser($user_arr);
       echo $rs;
   }
    public function dateaseuser($user_arr){
        $unionid=$user_arr['unionid'];
        $nickname=$user_arr['nickname'];
        $data=WeixinUser::where('unionid',$unionid)->first();
        if($data){
            return '登陆成功';
        }else{
            $user_info=[
                'name'=>$nickname
            ];
            $id=UserModel::insertGetId($user_info);
            if($id){
                $weixin_info=[
                    'uid'=>$id,
                    'openid'=>$user_arr['openid'],
                    'add_time'=>time(),
                    'nickname'=>$user_arr['nickname'],
                    'sex'=>$user_arr['sex'],
                    'headimgurl'=>$user_arr['headimgurl'],
                    'unionid'=>$user_arr['unionid'],
                    'subscribe_time'=>time()
                ];
                $wid=WeixinUser::insertGetId($weixin_info);
                if($wid){
                    return '存入数据库成功';
                }else{
                    return '登录失败wx_user';
                }
            }else{
                return '登录失败users';
            }
        }
    }
}
