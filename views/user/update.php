<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\UserForm */
/* @var $user_flag bool */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

?>

<div class="site-signup">
    <div class="row">

        <div class="col-xs-12">
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id' => 'update-form',
                    'action' => ['/user/update?id=' . $model->id],
                    'validationUrl' => ['/user/form-validate?scenario=update'],
                    'enableAjaxValidation' => true,
                    'fieldConfig' => [
                        'template' => "{label}\n{input}",
                    ],
                ]); ?>

                <?= $form->field($model, 'last_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Фамилия']) ?>

                <?= $form->field($model, 'first_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Имя']) ?>

                <?= $form->field($model, 'patron_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Отчество']) ?>

                <?= $form->field($model, 'passport', ['options' => ['class' => 'col-xs-12']])
                    ->textInput(['placeholder' => 'Паспорт']) ?>

                <?= $form->field($model, 'organization', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Оргнанизация']) ?>

                <?= $form->field($model, 'post', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Должность']) ?>

                <?= $form->field($model, 'phone', ['options' => ['class' => 'col-xs-6']])
                    ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
                    ->textInput(['placeholder' => 'Телефон']) ?>

                <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Эл.почта']) ?>

                <?= $form->field($model, 'password', ['options' => ['class' => 'col-xs-12']])
                    ->textInput(['placeholder' => 'Пароль']) ?>
            </div>

            <hr>

            <div class="form-group">
                <?= Html::submitButton('Обновить', [
                    'class' => 'col-xs-5 btn btn-default',
                    'name' => 'update',
                    'form' => 'update-form'
                ]); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
