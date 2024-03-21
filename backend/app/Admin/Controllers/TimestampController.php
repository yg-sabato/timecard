<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Timestamp;

class TimestampController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Timestamp Management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Timestamp);

        $grid->filter(function($filter){
            $filter->expand();
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created at')->datetime();
        });

        $grid->column('created_at', __('Created at'))->display(function($created_at){
                return date('m-d H:i:s', strtotime($created_at));
            })->sortable();
        $grid->column('stamp_type', __('Stamp type'))->sortable();
        $grid->column('description', __('Description'))->display(function($description){
            if($this->stamp_type === 'out'){
                return '';
            }
            return $description;
        })->sortable();
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Timestamp::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Timestamp);

        $form->display('id', __('ID'));
        $form->select('stamp_type', __('Stamp type'))->options(['in' => 'In', 'out' => 'Out']);
        $form->text('description', __('Description'));
        $form->display('created_at', __('Created At'));

        return $form;
    }
}
