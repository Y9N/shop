<?php

namespace App\Http\Controllers\Weixin;

use App\Model\CmsOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Weixin\WXBizDataCryptController;
use App\Model\OrderModel;
use GuzzleHttp;

class PayController extends Controller
{
    //

    public $weixin_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $weixin_notify_url = 'https://yc.qianqianya.xyz/weixin/pay/notice';     //支付通知回调

    /**
     * @param 第一次请求
     * 第一次请求
     */
    public function pay($order_number)
    {
        //echo __METHOD__;die;
        $total_fee = 1;         //用户要支付的总金额
        //$order_id = base64_decode($order_number); //订单号
        //setcookie('order_id',$order_number,time()+3600,'/','',false,true);
        $order_info = [
            'appid'         =>  env('WEIXIN_APPID_0'),      //微信支付绑定的服务号的APPID
            'mch_id'        =>  env('WEIXIN_MCH_ID'),       // 商户ID
            'nonce_str'     => str_random(16),             // 随机字符串
            'sign_type'     => 'MD5',
            'body'          => '测试订单-'.$order_number,
            'out_trade_no'  => $order_number,                       //本地订单号
            'total_fee'     => $total_fee,
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],     //客户端IP
            'notify_url'    => $this->weixin_notify_url,        //通知回调地址
            'trade_type'    => 'NATIVE'                         // 交易类型
        ];


        $this->values = [];
        $this->values = $order_info;
        $this->SetSign();

        $xml = $this->ToXml();      //将数组转换为XML
        $rs = $this->postXmlCurl($xml, $this->weixin_unifiedorder_url, $useCert = false, $second = 30);

        $data =  simplexml_load_string($rs);
        /*var_dump($data);echo '<hr>';
        echo "err_code:". $data->err_code.'<br>';
        echo "err_code_des:". $data->err_code_des.'<br>';
        echo "code_url:". $data->code_url.'<br>';die;
        echo 'return_code: '.$data->return_code;echo '<br>';
		echo 'return_msg: '.$data->return_msg;echo '<br>';
		echo 'appid: '.$data->appid;echo '<br>';
		echo 'mch_id: '.$data->mch_id;echo '<br>';
		echo 'nonce_str: '.$data->nonce_str;echo '<br>';
		echo 'sign: '.$data->sign;echo '<br>';
		echo 'result_code: '.$data->result_code;echo '<br>';
		echo 'prepay_id: '.$data->prepay_id;echo '<br>';
		echo 'trade_type: '.$data->trade_type;echo '<br>';
        echo "err_code:". $data->err_code.'<br>';
        echo "err_code_des:". $data->err_code_des.'<br>';
        echo "code_url:". $data->code_url.'<br>';*/
        $url=$data->code_url;
        return view('weixin.pay',['code_url'=>$url,'order_id'=>$order_number]);
        //$url=base64_encode($url);
        //die;
        //echo '<pre>';print_r($data);echo '</pre>';
        //echo $url;die;
        //header('refresh:0;url=/weixin/pay/code_url/'.$url.'');

        //将 code_url 返回给前端，前端生成 支付二维码

    }
    public function code_url($code_url){
        $code_url=base64_decode($code_url);
        $order_id=$_COOKIE['order_id'];
        //echo $code_url;die;
        return view('weixin.pay',['code_url'=>$code_url,'order_id'=>$order_id]);
    }

    public function ifsuccess(Request $request)
    {
        $order_id=$request->input('order_id');
        $data=CmsOrder::where('order_number',$order_id)->first();
        if($data['is_pay']==2){
            echo 2;
        }else{
            echo 1;
        }
    }

    public function success(){
        echo '支付成功';
    }


    protected function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values) <= 0)
        {
            die("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    private  function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		if($useCert == true){
//			//设置证书
//			//使用证书：cert 与 key 分别属于两个.pem文件
//			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//		}
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            die("curl出错，错误码:$error");
        }
    }


    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }


    private function MakeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".env('WEIXIN_MCH_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     * 微信支付回调
     */
    public function notice()
    {
        $data = file_get_contents("php://input");

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_pay_notice.log',$log_str,FILE_APPEND);

        $xml = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        //var_dump($xml);echo "<br>";
        //var_dump($xml->mch_id);die;
        if($xml['result_code']=='SUCCESS' && $xml['return_code']=='SUCCESS'){      //微信支付成功回调
            //验证签名
            $this->values = [];
            $this->values =$xml;
            $sign=$this->SetSign();
            if($xml['sign']==$sign){       //签名验证成功
                //TODO 逻辑处理  订单状态更新
                $oid = $xml['out_trade_no'];     //商户订单号
                $info = [
                    'is_pay'        => 1,       //支付状态  0未支付 1已支付
                    'pay_amount'    => $xml['total_fee'],    //支付金额
                    'pay_time'      => time(), //支付时间
                    'plat_oid'      => $xml['transaction_id'],      //微信订单号
                    'plat'          => 2,      //平台编号 1支付宝 2微信
                ];
                CmsOrder::where(['order_number'=>$oid])->update($info);
            }else{
                //TODO 验签失败
                echo '验签失败，IP: '.$_SERVER['REMOTE_ADDR'];
                // TODO 记录日志
            }
        }
        $response = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;
    }


}
