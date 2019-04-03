<?php

namespace App\Admin\Controllers;

use App\Model\Yuekao;
use App\Http\Controllers\Controller;
//use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class YueKaoController extends Controller
{
    //use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $data=Yuekao::all();
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('admin.yuekao',['data'=>$data]));
    }

    public function pass(Request $request)
    {
        $id=$request->input('id');
        //$info=Yuekao::where('id',$id)->first()->toArray();
        $r_num=Yuekao::where('id',$id)->update(['is_pass'=>2]);
        if($r_num===false){
            echo '审核失败';
        }else{
            //加载公钥
            $publickey = openssl_pkey_get_public(file_get_contents('./key/openssl.key'));
            $app_key=time().rand(1000,9999);
            $app_secret='yuekao'.time().$id;
            $data=[
                'app_key'=>$app_key,
                'app_secret'=>$app_secret
            ];
            $data1=json_encode($data);
            //Redis::set("app_key:id:$id",$app_key);
            //使用公钥加密
            $encryptedData = '';
            openssl_public_encrypt($data1, $encryptedData, $publickey);
            Redis::set("app_key:id:$id",base64_encode($encryptedData));
            //$as=Redis::get("app_key:id:$id");
            //var_dump($as);die;
            //var_dump($encryptedData);die;
            echo '操作成功！';
        }
    }
    public function nopass(Content $content)
    {
        $id=$_GET['id'];
        //$info=Yuekao::where('id',$id)->first()->toArray();
        $r_num=Yuekao::where('id',$id)->update(['is_pass'=>3]);
        if($r_num===false){
            echo '操作失败';
        }else{
            return $content
                ->header('未通过理由')
                ->description('description')
                ->body(view('admin.nopass',['id'=>$id]));
        }
    }
    public function nopass_do(Request $request)
    {
        $id=$request->input('id');
        $msg=$request->input('msg');
        $r_num=Yuekao::where('id',$id)->update(['msg'=>$msg]);
        if($r_num===false){
            echo '操作失败';
        }else{
            echo '操作成功';
        }
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
        $grid = new Grid(new Yuekao);

        $grid->id('Id');
        $grid->name('Name');
        $grid->number('Number');
        $grid->file('File');
        $grid->yongtu('Yongtu');
        $grid->reg_num('Reg num');
        $grid->is_pass('Is pass');

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
        $show = new Show(Yuekao::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->number('Number');
        $show->file('File');
        $show->yongtu('Yongtu');
        $show->reg_num('Reg num');
        $show->is_pass('Is pass');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Yuekao);

        $form->text('name', 'Name');
        $form->text('number', 'Number');
        $form->file('file', 'File');
        $form->text('yongtu', 'Yongtu');
        $form->number('reg_num', 'Reg num');
        $form->number('is_pass', 'Is pass');

        return $form;
    }
}
