<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\UserForm */
/* @var $user_flag bool */
/* @var $post  */

use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\AutoComplete;
use app\entities\Conference;
use app\entities\User;

?>

<div class="site-signup">
    <div class="row">

        <div class="col-xs-12">
            <?php try {
                echo AutoComplete::widget([
                    'name' => 'name',
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Поиск...',
                    ],
                    'clientOptions' => [
                        'source' => Url::to(['user/autocomplete']),
                        'appendTo' => '#signup-form',
                        'autoFill' => true,
                        'minLength' => '0',
                        'select' => new JsExpression('function(event, ui) {
                            formLoad(\'/user/signup-form\', ui.item.label, ui.item.value);
                        }'),
                    ],

                ]);
            } catch (Exception $e) {
                echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            } ?>

            <hr>

        </div>

        <?php $conference_now = Conference::find()
            ->where('start_time <= ' . (time() + 7200 ))
            ->andWhere('end_time IS NULL')
            ->andWhere(['status' => 10])
            ->all(); ?>

        <div class="col-xs-12">
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id' => 'signup-form',
                    'action' => ($user_flag || !empty($conference_now)) ? ['/user/signup-conference'] : ['/user/signup'],
                    'validationUrl' => ($user_flag || !empty($conference_now)) ? ['/user/form-validate?scenario=conference'] : ['/user/form-validate?scenario=create'],
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

                <?php if (Yii::$app->user->can('admin')):
                    echo $form->field($model, 'role', ['options' => ['class' => 'col-xs-12']])
                    ->dropDownList(User::$roleList);
                endif; ?>
            </div>

            <hr>

            <div class="form-group">
                <?php
                if ($user_flag || !empty($conference_now)):
                    echo Html::submitButton('Зарегистрировать на конференцию', [
                        'class' => 'col-xs-5 btn btn-default',
                        'name' => 'signup',
                        'value' => 'conference',
                        'form' => 'signup-form'
                    ]);
                else:
                    echo Html::submitButton('Зарегистрировать', [
                        'class' => 'col-xs-3 btn btn-default',
                        'name' => 'signup',
                        'value' => 'register',
                        'form' => 'signup-form'
                    ]);
                endif;
                echo Html::button('Очистить форму', [
                    'class' => 'col-xs-3 pull-right btn btn-default',
                    'onclick' => 'formLoad(\'/user/signup-form\', \'Регистрационная карточка\')'
                ]);
                ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
