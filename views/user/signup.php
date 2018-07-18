<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\UserForm */
/* @var $conference \app\entities\Conference|array */

use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use app\entities\User;
use app\forms\UserForm;

$this->title = 'Регистрация пользователей';

?>

<div class="site-signup">
    <div class="row">
        <?php if ((Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST)) && ($model->scenario == UserForm::SCENARIO_CREATE_PAGE || $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE)): ?>
            <div class="col-xs-12">
                <label>Поиск пользователя</label>
                <?php try {
                    echo AutoComplete::widget([
                        'name' => 'name',
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Введите данные...',
                        ],
                        'clientOptions' => [
                            'source' => Url::to(['user/autocomplete']),
                            'appendTo' => '#signup-form',
                            'autoFill' => true,
                            'minLength' => '2',
                            'select' => new JsExpression('function(event, ui) {
                                formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE . '\', \'' . UserForm::LOAD_FORM_TO_PAGE . '\', ui.item.label, ui.item.id);
                            }'),
                        ],
                    ]);
                } catch (Exception $e) {
                    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                } ?>
                <p style="color: red; font-style: italic">Порядок ввода: ФАМИЛИЯ ИМЯ ОТЧЕСТВО</p>

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

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE): ?>
                    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
                <?php endif; ?>

                <?= $form->field($model, 'last_name', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Фамилия', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false])
                    ->label('<span style="color: red;">*</span> Фамилия') ?>

                <?= $form->field($model, 'first_name', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Имя', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false])
                    ->label('<span style="color: red;">*</span> Имя') ?>

                <?= $form->field($model, 'patron_name', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Отчество', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false])
                    ->label('<span style="color: red;">*</span> Отчество') ?>

                <?= $form->field($model, 'organization', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Организация', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false]) ?>

                <?= $form->field($model, 'post', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Должность', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false]) ?>

                <?= $form->field($model, 'speciality', ['options' => ['class' => 'col-xs-12 col-sm-4']])
                    ->textInput(['placeholder' => 'Специализация', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false]) ?>

                <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-12 col-sm-6']])
                    ->textInput(['placeholder' => 'Эл.почта', 'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false]) ?>

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER || ($model->scenario == UserForm::SCENARIO_UPDATE && (Yii::$app->user->id == $model->id || Yii::$app->user->can(User::ROLE_ADMIN)))):
                    echo $form->field($model, 'password', ['options' => ['class' => 'col-xs-12 col-sm-6']])
                        ->textInput(['placeholder' => 'Пароль'])
                        ->label(($model->scenario == UserForm::SCENARIO_REGISTER ? '<span style="color: red;">*</span> ' : null) . 'Пароль');
                endif; ?>

                <?php if ((Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST)) && $model->scenario != UserForm::SCENARIO_UPDATE):
                    echo $form->field($model, 'conference', ['options' => ['class' => 'col-xs-12']])
                        ->dropDownList(ArrayHelper::map($conference, 'id', 'title'), !$conference ? ['prompt' => 'Регистрация не мероприятия закрыта'] : []);
                endif; ?>

                <?php if (Yii::$app->user->can(User::ROLE_ADMIN) && $model->scenario == UserForm::SCENARIO_CREATE_PAGE):
                    echo $form->field($model, 'role', ['options' => ['class' => 'col-xs-12']])
                        ->dropDownList(User::$roleList);
                endif; ?>
            </div>

            <div class="col-margin-top-15"><span style="color: red;">*</span> - поля обязательные для заполнения</div>

            <hr>

            <div class="form-group">
                <?= Html::submitButton($model->scenario == UserForm::SCENARIO_UPDATE ? 'Сохранить' : 'Зарегистрировать' , [
                    'class' => 'col-xs-12 col-sm-6 col-md-5 btn btn-default col-margin-bottom-10',
                    'name' => 'signup',
                    'value' => 'conference',
                    'form' => 'signup-form'
                ]); ?>

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE):
                    echo Html::button('Очистить форму', [
                        'class' => 'col-xs-12 col-sm-6 col-md-3 pull-right btn btn-default col-margin-bottom-10',
                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE_PAGE . '&clearForm=true\', \'' . UserForm::LOAD_FORM_TO_PAGE . '\')'
                    ]);
                endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
