<?php
    namespace App\Http\Controllers\Pay;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

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

        public function test()
        {

            $bizcont = [
                'subject'           => 'ancsd'. mt_rand(1111,9999).str_random(6),
                'out_trade_no'      => 'oid'.date('YmdHis').mt_rand(1111,2222),
                'total_amount'      => 0.05,
                'product_code'      => 'QUICK_WAP_WAY',

            ];

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
    }
?>