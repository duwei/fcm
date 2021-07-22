<?php

namespace App\Admin\Controllers;

use App\Models\Game;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GameController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Game';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Game());

//        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('uuid', __('Uuid'));
        $grid->column('key', __('Key'));
        $grid->column('min_age', __('Min age'));
        $grid->column('open_time', __('Open time'));
        $grid->column('close_time', __('Close time'));
        $grid->column('max_hour_weekday', __('Max hour weekday'));
        $grid->column('max_hour_weekend', __('Max hour weekend'));
        $grid->column('max_money_daily', __('Max money daily'));
        $grid->column('max_money_monthly', __('Max money monthly'));
        $grid->column('start_prompt', __('Start prompt'));
        $grid->column('out_of_time_prompt', __('Out of time prompt'));
        $grid->column('time_limit_prompt', __('Time limit prompt'));
        $grid->column('money_limit_prompt', __('Money limit prompt'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Game::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('uuid', __('Uuid'));
        $show->field('key', __('Key'));
        $show->field('min_age', __('Min age'));
        $show->field('open_time', __('Open time'));
        $show->field('close_time', __('Close time'));
        $show->field('max_hour_weekday', __('Max hour weekday'));
        $show->field('max_hour_weekend', __('Max hour weekend'));
        $show->field('max_money_daily', __('Max money daily'));
        $show->field('max_money_monthly', __('Max money monthly'));
        $show->field('start_prompt', __('Start prompt'));
        $show->field('out_of_time_prompt', __('Out of time prompt'));
        $show->field('time_limit_prompt', __('Time limit prompt'));
        $show->field('money_limit_prompt', __('Money limit prompt'));
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
        $form = new Form(new Game());

        $form->text('name', __('Name'))->rules('required');
//        $form->text('uuid', __('Uuid'));
//        $form->text('key', __('Key'));
        $form->number('min_age', __('Min age'))->min(6)->rules('required');
        $form->timeRange('open_time', 'close_time', __('Open close time'))->rules('required');
        $form->decimal('max_hour_weekday', __('Max hour weekday'))->rules('required');
        $form->decimal('max_hour_weekend', __('Max hour weekend'))->rules('required');
        $form->decimal('max_money_daily', __('Max money daily'))->rules('required');
        $form->decimal('max_money_monthly', __('Max money monthly'))->rules('required');
        $form->textarea('start_prompt', __('Start prompt'))->rules('required');
        $form->textarea('out_of_time_prompt', __('Out of time prompt'))->rules('required');
        $form->textarea('time_limit_prompt', __('Time limit prompt'))->rules('required');
        $form->textarea('money_limit_prompt', __('Money limit prompt'))->rules('required');

        return $form;
    }
}
