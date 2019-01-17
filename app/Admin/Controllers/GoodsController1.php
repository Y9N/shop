<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use App\Model\CmsGoods;

class GoodsController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new CmsGoods());

        $grid->model()->orderBy('goods_id','desc');     //倒序排序

        $grid->goods_id('商品ID');
        $grid->goods_name('商品名称');
        $grid->score('库存');
        $grid->goods_price('价格');
        $grid->addtime('添加时间')->display(function($time){
            return $time;
        });

        return $grid;
    }


    public function edit($id, Content $content)
    {

        //echo __METHOD__;die;
        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }



    //创建
    public function create(Content $content)
    {

        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
    }

    public function update($id)
    {
        //echo '<pre>';print_r($_POST);echo '</pre>';
        $data=[
            'goods_name'=>$_POST['goods_name'],
            'score'=>$_POST['score'],
            'goods_price'=>$_POST['goods_price'],
            'upd_time'=>time()
        ];
        CmsGoods::where(['goods_id'=>$id])->update($data);
    }
    //添加执行
    public function store()
    {
        //echo '<pre>';print_r($_POST);echo '</pre>';
        $goods_name=$_POST['goods_name'];
        $goods_price=$_POST['goods_price'];
        $score=$_POST['score'];
        $data=[
            'goods_name'=>$goods_name,
            'goods_price'=>$goods_price,
            'score'=>$score,
        ];
        $res=CmsGoods::insert($data);
    }



    public function show($id, Content $content)
    {
       return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    //删除
    public function destroy($id)
    {
        CmsGoods::where(['goods_id'=>$id])->delete();
        $response = [
            'status' => true,
            'message'   => 'ok'
        ];
        return $response;
    }



    protected function form()
    {
        $form = new Form(new CmsGoods());

        //$form->display('goods_id', '商品ID');
        $form->text('goods_name', '商品名称');
        $form->number('score', '库存');
        $form->currency('goods_price', '价格')->symbol('¥');

        return $form;
    }
}
