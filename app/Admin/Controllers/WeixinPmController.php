<?php

namespace App\Admin\Controllers;

use App\Model\WxPmMedia;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WeixinPmController extends Controller
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
        $grid = new Grid(new WxPmMedia);

        $grid->id('Id');
        $grid->media_id('Media id');
        $grid->local_file_name('material')->display(function($local_file_name){
            return '<img  src="https://yc.qianqianya.xyz/form_test/'.$local_file_name.'" width=100px>';
        });
        $grid->url('Url');
        $grid->add_time('Add time');
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
        $show = new Show(WxPmMedia::findOrFail($id));

        $show->id('Id');
        $show->media_id('Media id');
        $show->url('Url');
        $show->add_time('Add time');
        $show->local_file_name('Local file name');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WxPmMedia);

        $form->text('media_id', 'Media id');
        $form->url('url', 'Url');
        $form->number('add_time', 'Add time');
        $form->text('local_file_name', 'Local file name');

        return $form;
    }
}
