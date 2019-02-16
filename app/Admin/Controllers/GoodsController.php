<?php

namespace App\Admin\Controllers;

use App\Model\CmsGoods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;


class GoodsController extends Controller
{
    use HasResourceActions;

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
        $grid = new Grid(new CmsGoods);
        $grid->expandFilter();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('goods_name', 'goods_name');
        });
        $grid->goods_id('Goods id');
        $grid->goods_name('Goods name');
        $grid->score('Score');
        $grid->addtime('Addtime');
        $grid->goods_price('Goods price');
        $grid->upd_time('Upd time');
        $grid->paginate(5);
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
        $show = new Show(CmsGoods::findOrFail($id));

        $show->goods_id('Goods id');
        $show->goods_name('Goods name');
        $show->score('Score');
        $show->addtime('Addtime');
        $show->goods_price('Goods price');
        $show->upd_time('Upd time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CmsGoods);
        $form->text('goods_name', 'Goods name');
        $form->number('score', 'Score');
        $form->datetime('addtime', 'Addtime')->default(date('Y-m-d H:i:s'));
        $form->number('goods_price', 'Goods price');
        $form->number('upd_time', 'Upd time');
        $form->ckeditor('content');
        return $form;
    }
}
