<?php

namespace App\Http\Controllers\Encrypt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Illuminate\Support\Facades\Redis;

class EncryptController extends Controller
{
    public function encrypt()
    {
        //var_dump($_POST);die;
        $array=$_POST;
        $passwd=$array['passwd'];
        $key=$array['key'];
        $iv=$array['iv'];
        //echo $passwd;die;
        //解密
        $info=openssl_decrypt($passwd,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        //echo $info;die;

        $data=[
            'error'=>'0',
            'msg'=>'ok'
        ];
        //print_r($data);die;
        $key2='I don\'t know too';
        $iv2=mt_rand('1111111111111111','9999999999999999');
        $data1=json_encode($data);
        //print_r($data1);die;
        //加密
        $passwd2=base64_encode(openssl_encrypt($data1,'AES-128-CBC',$key2,OPENSSL_RAW_DATA,$iv2));
        //echo $passwd2;die;
        $data=array(
            'data'=>"$passwd2",
            'key2'=>"$key2",
            'iv2'=>"$iv2"
        );
        //var_dump($data);die;
        echo  json_encode($data);
    }


    public function sign()
    {
        $signature=$_POST['signature'];
        $dig='my name is lixianyu ,my sex is boy!!';
        // 加载公钥
        $privatekey = openssl_pkey_get_public(file_get_contents('./key/openssl.key'));

        // 摘要及签名的算法
        $digestAlgo = 'sha256'; //生成摘要
        $algo = OPENSSL_ALGO_SHA1; //签名算法

        // 生成摘要
        $digest = openssl_digest($dig, $digestAlgo);

        // 验签
        $verify = openssl_verify($digest, base64_decode($signature), $privatekey, $algo);
        if(!$verify){
            $info=[
                'code'=>'5001',
                'msg'=>'Attestation error',
            ];
            die(json_encode($info));
        }
        $encryptedData=base64_decode($_POST['encryptedData']);
        // 使用公钥进行解密
        $sensitiveData = '';//解密后的数据
        openssl_public_decrypt($encryptedData, $sensitiveData, $privatekey);
        print_r($sensitiveData);
    }
}