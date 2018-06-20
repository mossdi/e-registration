<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use app\forms\UserForm;

$this->title = 'Авторизация';

?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::img('/image/niiozmm_logo_full.png', ['class' => 'img-responsive']) ?>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg"><?= Html::decode('Пожалуйста авторизируйтесь для начала сессии') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'phone', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-phone form-control-feedback'></span>"
            ])
            ->label(false)
            ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
            ->textInput(['placeholder' => 'Телефон']) ?>

        <?= $form
            ->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
            ])
            ->label(false)
            ->passwordInput(['placeholder' => 'Пароль']) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')
                    ->checkbox()
                    ->label('Запомнить меня') ?>
            </div>

            <div class="col-xs-4">
                <?= Html::submitButton('Войти', [
                     'class' => 'btn btn-default btn-block btn-flat',
                    'name' => 'login-button'
                ]) ?>
            </div>

            <div class="col-xs-12 text-right">
                <?= Html::a('Зарегистрироваться', ['#'], [
                    'data-toggle' => 'modal',
                    'data-target' => '#modalForm',
                    'onclick'     => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_REGISTER . '\', \'Регистрационная карточка\')',
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
