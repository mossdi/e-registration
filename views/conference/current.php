<?php

/* @var $this \yii\web\View */
/* @var $model \app\entities\Conference */

use app\entities\User;
use yii\helpers\Html;
use app\forms\UserForm;

$this->title = $model ? $model->title : 'Нет открытых конференций';

?>

<div id="conference-current">
    <?php if ($model): ?>
        <?php if (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SPEAKER) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)):  ?>
            <div class="control-panel">
                <?= Html::a('Список участников', ['/#'], [
                    'class' => 'btn btn-info col-margin-bottom-10',
                    'data-toggle' => 'modal',
                    'data-target' => '#modalForm',
                    'onclick'     => 'formLoad(\'/conference/participant\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'Участники конференции - ' . $model->title . '\', \'' . $model->id . '\')'
                ]) ?>

                <?php /* кнопка отключена */ Html::a('Тестирование', ['/test/view?id=' . $model->id], [
                    'class' => 'btn btn-primary col-margin-bottom-10',
                ]) ?>

                <?= Html::a('Закрыть конференцию', ['/conference/close?id=' . $model->id], [
                    'class' => 'btn btn-danger col-margin-bottom-10',
                    'data-method' => 'post',
                    'data-confirm' => 'Хотите закрыть конференцию?'
                ]) ?>
            </div>
        <?php endif; ?>

        <hr>

        <h1><?= $model->title ?></h1>

        <hr>

        <div class="description">
            <?= $model->description ?>
        </div>
    <?php else: ?>
        <h3 class="col-margin-bottom-40" style="font-weight: bold; color: red;">
            <?= $this->title ?>
        </h3>

        <?= $this->render(
            '/html_block/conference_future'
        ) ?>
    <?php endif; ?>
</div>
