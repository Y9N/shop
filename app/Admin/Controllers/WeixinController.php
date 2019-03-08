<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use App\Http\Controllers\Controller;
use App\Model\WxMsg;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use GuzzleHttp;

class WeixinController extends Controller
{
    use HasResourceActions;
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    protected $redis_weixin_user_info = 'str:redis_weixin_user_info';     //微信 用户信息
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinUser);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid')->display(function($openid){
            return '<a href="touser?openid='.$openid.'">'.$openid.'</a>';
        });
        $grid->add_time('Add time')->display(function($add_time){
            return date('Y-m-d h:i:s',$add_time);
        });
        $grid->nickname('Nickname');
        $grid->sex('Sex')->display(function($sex){
            if($sex==1){
                return '男';
            }elseif($sex==2){
                return '女';
            }elseif($sex==0){
                return '保密';
            }
        });;
        $grid->headimgurl('Headimgurl')->display(function($img_url){
            return '<img src='.$img_url.'>';
        });
        $grid->subscribe_time('Subscribe time')->display(function($subscribe_time){
            return date('Y-m-d h:i:s',$subscribe_time);
        });;
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinUser::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinUser);

        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->number('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');

        return $form;
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }
    /*视图层*/
    public function view()
    {
        return view('admin.autosend');
    }
    /*私聊视图层*/
    public function touserview(Content $content)
    {
        $openid=$_GET['openid'];
        $data=WeixinUser::where('openid',$openid)->first();
        $name=$data['nickname'];
        $head=$data['headimgurl'];
        //echo $openid;
        $array=WxMsg::where('openid',$openid)->where('msg_type',1)->get()->toArray();
        $kefu=WxMsg::where('openid',$openid)->where('msg_type',2)->get()->toArray();
        //print_r($array);die;
        $arr=[
            'openid'=>$openid,
            'head'=>$head,
            'name'=>$name,
            'array'=>$array,
            'kefu'=>$kefu
        ];
        return $content
            ->header($name)
            ->description('description')
            ->body(view('admin.touser',$arr));
    }
    /*私聊*/
    public function touser(Request $request)
    {
        $openid=$request->input('openid');
        $text=$request->input('text');
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token;
        //var_dump($url);exit;
        $client = new GuzzleHttp\Client(['base_url' => $url]);
        $param = [
            "touser"=>$openid,
            "msgtype"=>"text",
            "text"=>[
                "content"=>$text
            ],
        ];
        ///var_dump($param);exit;
        $r = $client->Request('POST', $url, [
            'body' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ]);
        //var_dump($r);exit;
        $response_arr = json_decode($r->getBody(), true);
        //echo '<pre>';
        //print_r($response_arr);
        // echo '</pre>';
        if ($response_arr['errcode'] == 0) {
            $data=[
                'openid'=>$openid,
                'massage'=>$text,
                'msg_type'=>2,
                'add_time'=>time()
            ];
            WxMsg::insertGetId($data);
            return "发送成功";
        } else {
            echo "发送失败";
            echo '</br>';
            echo $response_arr['errmsg'];

        }

    }
    /**更新消息*/
    public function usermsg(Request $request){
        $openid=$request->input('openid');
        $data=WeixinUser::where('openid',$openid)->first();
        $name=$data['nickname'];
        $array=WxMsg::where('openid',$openid)->where('msg_type',1)->get()->toArray();
        foreach($array as $k=>$v){
            $array[$k]['add_time']=date('Y-m-d h:i:s',$v['add_time']);
        }
        $arr['array']=$array;
        $arr['name']=$name;
        echo json_encode($arr);
    }

    public function redisuser(Content $content)
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->getWXAccessToken();
        $data=json_decode(file_get_contents($url),true);
        $userinfo=Redis::get($this->redis_weixin_user_info);
        $userinfo=json_decode($userinfo,true);
        $userinfo['sign']=$data['tags'];
        //print_r($userinfo);die;
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('weixin.redisuser',$userinfo));
    }
}
