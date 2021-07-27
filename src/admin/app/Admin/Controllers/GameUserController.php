<?php

namespace App\Admin\Controllers;

use App\Models\Game;
use App\Models\GameUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GameUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'GameUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GameUser());

        $grid->column('id', __('Id'));
//        $grid->column('game_id', __('Game id'));
        $grid->column('game.name', __('Game'));
        $grid->column('account', __('Account'));
        $grid->column('name', __('Name'));
        $grid->column('age', __('Age'));
        $grid->column('id_card', __('Id card'));

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
        $show = new Show(GameUser::findOrFail($id));

        $show->field('id', __('Id'));
//        $show->field('game_id', __('Game id'));
        $show->field('game.name', __('Game'));
        $show->field('account', __('Account'));
        $show->field('password', __('Password'));
        $show->field('name', __('Name'));
        $show->field('age', __('Age'));
        $show->field('id_card', __('Id card'));
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
        $form = new Form(new GameUser());

//        $form->number('game_id', __('Game id'));
        $form->select('game_id', __('Game'))->options(Game::all()->pluck('name', 'id'));
        $form->text('account', __('Account'))->rules('required');
        $form->password('password', __('Password'))->rules('required');
        $form->text('name', __('Name'))->rules('required');
        $form->number('age', __('Age'))->min(6)->rules('required');
        $form->text('id_card', __('Id card'))->rules('required');

        return $form;
    }
}
