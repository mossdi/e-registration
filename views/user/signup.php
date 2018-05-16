<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\UserForm */
/* @var $conference \app\entities\Conference|array */

use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\AutoComplete;
use app\entities\User;
use app\forms\UserForm;

?>

<div class="site-signup">
    <div class="row">

        <?php if ((Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST)) && $model->scenario == UserForm::SCENARIO_CREATE): ?>
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
                            formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_PARTICIPANT . '\', ui.item.label, ui.item.value);
                        }'),
                        ],

                    ]);
                } catch (Exception $e) {
                    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                } ?>

                <hr>
            </div>
        <?php endif; ?>

        <div class="col-xs-12">
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id' => 'signup-form',
                    'action' => $model->scenario == UserForm::SCENARIO_UPDATE ? ['/user/update?id=' . $model->id] : ['/user/signup?scenario=' . $model->scenario],
                    'validationUrl' => ['/user/form-validate?scenario=' . $model->scenario],
                    'enableAjaxValidation' => true,
                    'fieldConfig' => [
                        'template' => "{label}\n{input}",
                    ],
                ]); ?>

                <?= $form->field($model, 'last_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Фамилия', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'first_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Имя', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'patron_name', ['options' => ['class' => 'col-xs-4']])
                    ->textInput(['placeholder' => 'Отчество', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'passport', ['options' => ['class' => 'col-xs-12']])
                    ->widget(MaskedInput::className(), ['mask' => '9999999999'])
                    ->textInput(['placeholder' => 'Паспорт', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'organization', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Оргнанизация', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'post', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Должность', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'phone', ['options' => ['class' => 'col-xs-6']])
                    ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
                    ->textInput(['placeholder' => 'Телефон', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-6']])
                    ->textInput(['placeholder' => 'Эл.почта', 'readonly' => $model->scenario == UserForm::SCENARIO_PARTICIPANT ? true : false]) ?>

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER || ($model->scenario == UserForm::SCENARIO_UPDATE && Yii::$app->user->id == $model->id)):
                    echo $form->field($model, 'password', ['options' => ['class' => 'col-xs-12']])
                        ->textInput(['placeholder' => 'Пароль']);
                endif; ?>

                <?php if ((Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST)) && $model->scenario != UserForm::SCENARIO_UPDATE):
                    echo $form->field($model, 'conference', ['options' => ['class' => 'col-xs-12']])
                        ->dropDownList(ArrayHelper::map($conference, 'id', 'title'), ['prompt' => 'Выберите конференцию']);
                endif; ?>

                <?php if (Yii::$app->user->can(User::ROLE_ADMIN) && $model->scenario == UserForm::SCENARIO_CREATE):
                    echo $form->field($model, 'role', ['options' => ['class' => 'col-xs-12']])
                        ->dropDownList(User::$roleList);
                endif; ?>
            </div>

            <hr>

            <div class="form-group">
                <?= Html::submitButton($model->scenario == UserForm::SCENARIO_UPDATE ? 'Сохранить' : 'Зарегистрировать' , [
                    'class' => 'col-xs-5 btn btn-default',
                    'name' => 'signup',
                    'value' => 'conference',
                    'form' => 'signup-form'
                ]); ?>

                <?php if ($model->scenario == UserForm::SCENARIO_PARTICIPANT):
                    echo Html::button('Очистить форму', [
                        'class' => 'col-xs-3 pull-right btn btn-default',
                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE . '\', \'Регистрационная карточка\')'
                    ]);
                endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
