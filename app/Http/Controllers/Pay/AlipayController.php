<?php
    namespace App\Http\Controllers\Pay;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Model\CmsOrder;

    use GuzzleHttp\Client;
    class AlipayController extends Controller{
        //
        public $app_id;
        public $gate_way;
        public $notify_url;
        public $rsaPrivateKeyFilePath;
        public $return_url;
        public $alipay_gongyao;

        public function __construct()
        {
            $this->app_id=env('ALIPAY_APID');
            $this->gate_way=env('ALIPAY_GATE_WAY');
            $this->notify_url=env('ALIPAY_NOTIFY_URL');
            $this->rsaPrivateKeyFilePath=env('ALIPAY_RSA_Private_Key_File_Path');
            $this->return_url = env('ALIPAY_RETURN_URL');
            $this->alipay_gongyao = env('ALIPAY_GONGYAO');
        }

        /*
         * 请求订单服务 处理订单逻辑
         * */
        public function test0()
        {
            //
            $url = 'order.com';
//            $client = new Client();
            $client = new Client([
                'base_uri' => $url,
                'timeout'  => 2.0,
            ]);

            $response = $client->request('GET', '/order.php');
            echo $response->getBody();
        }

        public function pay($order_number)
        {
            $order_num=base64_decode($order_number);
            $order_info = CmsOrder::where(['order_number'=>$order_num])->where('uid',session()->get('u_id'))->first();
            if(!$order_info){
                die("订单 ".$order_num. "不存在！");
            }
            if($order_info->pay_time > 0){
                die("此订单已被支付，无法再次支付");
            }
            $bizcont = [
                'subject'           => 'ceshi:'.$order_info['order_number'],
                'out_trade_no'      => $order_info['order_number'],
                'total_amount'      => $order_info['order_amount'],
                'product_code'      => 'QUICK_WAP_WAY',

            ];
            //print_r($bizcont);die;
            $data = [
                'app_id'   => $this->app_id,
                'method'   => 'alipay.trade.wap.pay',
                'format'   => 'JSON',
                'charset'   => 'utf-8',
                'sign_type'   => 'RSA2',
                'timestamp'   => date('Y-m-d H:i:s'),
                'version'   => '1.0',
                'notify_url'   => $this->notify_url,
                'return_url'   => $this->return_url,
                'biz_content'   => json_encode($bizcont),
            ];
            $sign = $this->rsaSign($data);
            $data['sign'] = $sign;
            $param_str = '?';
            foreach($data as $k=>$v){
                $param_str .= $k.'='.urlencode($v) . '&';
            }
            //print_r($param_str);die;
            $url = rtrim($param_str,'&');
            $url = $this->gate_way . $url;
            header("Location:".$url);
        }

        public function rsaSign($params) {
            return $this->sign($this->getSignContent($params));
        }

        protected function sign($data) {

            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);

            ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);

            if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
                openssl_free_key($res);
            }
            $sign = base64_encode($sign);
            return $sign;
        }


        public function getSignContent($params) {
            ksort($params);
            $stringToBeSigned = "";
            $i = 0;
            foreach ($params as $k => $v) {
                if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                    // 转换成目标字符集
                    $v = $this->characet($v, 'UTF-8');
                    if ($i == 0) {
                        $stringToBeSigned .= "$k" . "=" . "$v";
                    } else {
                        $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                    }
                    $i++;
                }
            }

            unset ($k, $v);
            return $stringToBeSigned;
        }

        protected function checkEmpty($value) {
            if (!isset($value))
                return true;
            if ($value === null)
                return true;
            if (trim($value) === "")
                return true;

            return false;
        }


        /**
         * 转换字符集编码
         * @param $data
         * @param $targetCharset
         * @return string
         */
        function characet($data, $targetCharset) {

            if (!empty($data)) {
                $fileType = 'UTF-8';
                if (strcasecmp($fileType, $targetCharset) != 0) {
                    $data = mb_convert_encoding($data, $targetCharset, $fileType);
                }
            }


            return $data;
        }
        /**
         * 支付宝同步通知回调
         */
        public function aliReturn()
        {
            echo '<pre>';print_r($_GET);echo '</pre>';
            //验签 支付宝的公钥
            if(!$this->verify($_GET)){
                echo 'error';
            }else{
                echo $_GET['app_id'];
                echo '支付成功，正在跳转';
                header('refresh:2;url=/orderlist');
            }

            //处理订单逻辑
            //$this->dealOrder($_GET);
        }

        /**
         * 支付宝异步通知
         */
        public function aliNotify()
        {
            $data = json_encode($_POST);
            $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
            //记录日志
            file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            //验签
            $res = $this->verify($_POST);

            $log_str = '>>>> ' . date('Y-m-d H:i:s');
            if($res === false){
                //记录日志 验签失败
                $log_str .= " Sign Failed!<<<<< \n\n";
                file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            }else{
                $log_str .= " Sign OK!<<<<< \n\n";
                file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            }


            if($_POST['trade_status']=='TRADE_SUCCESS'){
                //更新订单状态
                $oid = $_POST['out_trade_no'];     //商户订单号
                $info = [
                    'is_pay'        => 1,       //支付状态  0未支付 1已支付
                    'pay_amount'    => $_POST['total_amount'],    //支付金额
                    'pay_time'      => strtotime($_POST['gmt_payment']), //支付时间
                    'plat_oid'      => $_POST['trade_no'],      //支付宝订单号
                    'plat'          => 1,      //平台编号 1支付宝 2微信
                ];

                CmsOrder::where(['order_number'=>$oid])->update($info);
            }
            //处理订单逻辑
            $this->dealOrder($_POST);

            echo 'success';
        }


        //验签
        function verify($params) {
            $sign = $params['sign'];
            $params['sign_type'] = null;
            $params['sign'] = null;

            //读取公钥文件
            $pubKey = file_get_contents($this->alipay_gongyao);
            $pubKey = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
            //转换为openssl格式密钥

            $res = openssl_get_publickey($pubKey);
            ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

            //调用openssl内置方法验签，返回bool值

            $result = (openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256)===1);
            openssl_free_key($res);

            return $result;
        }

        protected function rsaCheckV1($params, $rsaPublicKeyFilePath,$signType='RSA') {
            $sign = $params['sign'];
            $params['sign_type'] = null;
            $params['sign'] = null;
            return $this->verify($this->getSignContent($params), $sign, $rsaPublicKeyFilePath,$signType);
        }
        public function dealOrder($_POST){
            print_r($_POST);
        /*$res=CmsOrder::where(['order_number'=>$order_number])->update(['pay_time'=>time(),'pay_amount'=>rand(1111,9999),'is_pay'=>1]);
		if($res){
			echo '支付成功';
			$pay_amount=CmsOrder::where(['order_number'=>$order_number])->value('pay_amount');
			$integral=$pay_amount*100;
			$old_integral=CmsShop::where('id',$this->uid)->value('integral');
			$new_integral=$integral+$old_integral;
			CmsShop::where('id',$this->uid)->update(['integral'=>$new_integral]);
		}else{
			echo '支付失败';
		}*/
        }
    }
?>