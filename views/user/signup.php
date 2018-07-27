<?php

/* @var $this \yii\web\View */
/* @var $model \app\forms\UserForm */
/* @var $conference \app\entities\Conference|array */

use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use app\entities\User;
use app\forms\UserForm;
use yii2mod\alert\Alert;
use yii\widgets\Pjax;

$this->title = 'Регистрация пользователей';

?>

<?php Pjax::begin([
    'id' => 'signupParticipantContainer',
    'enablePushState' => false,
    'timeout' => 9999,
]); ?>

<?php try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
} ?>

<div class="site-signup">
    <div class="row">
        <?php if (
            (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)) &&
            ($model->scenario == UserForm::SCENARIO_CREATE_PAGE || $model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE || $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE)
        ) : ?>
            <div class="col-xs-12">
                <label>Поиск пользователя</label>
                <div class="row">
                    <div class="col-xs-12 col-sm-9 col-margin-bottom-10">
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
                                        formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE . '\', \'' . UserForm::LOAD_FORM_TO_PAGE . '\', ui.item.label, ui.item.id); participantCountReload();
                                    }'),
                                ],
                            ]);
                        } catch (Exception $e) {
                            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                        } ?>
                    </div>
                    <?php if ($conference): ?>
                        <div class="col-xs-12 col-sm-3 col-margin-bottom-10">
                            <?= Html::button('Список участников', [
                                'class' => 'btn btn-info col-xs-12',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalForm',
                                'onclick'     => 'formLoad(\'/conference/participant\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'Участники конференции - ' . $conference->title . '\', \'' . $conference->id . '\'); participantCountReload();'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <p style="color: red; font-style: italic">Порядок ввода: ФАМИЛИЯ ИМЯ ОТЧЕСТВО</p>

                <hr>
            </div>
        <?php endif; ?>

        <div class="col-xs-12">
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id' => ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE || $model->scenario == UserForm::SCENARIO_UPDATE) ? 'update-form' : 'signup-form',
                    'action' => ($model->scenario == UserForm::SCENARIO_UPDATE || $model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE) ? ['/user/update?id=' . $model->id . '&scenario=' . $model->scenario] : ['/user/signup?scenario=' . $model->scenario],
                    'validationUrl' => ['/user/form-validate?scenario=' . $model->scenario],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'options' => [
                        'data-pjax' => true,
                    ],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}",
                    ],
                ]); ?>

                <?php if ($model->scenario == UserForm::SCENARIO_CREATE_PAGE): ?>
                    <div class="col-xs-12"><p style="font-weight: bold">Новый пользователь...</p></div>
                <?php elseif ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE || $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE): ?>
                    <div class="col-xs-12 col-margin-bottom-10">
                        <p>
                            <span>Пользователь:</span> <span style="font-weight: bold; margin-right: 5px;"> <?= $model->last_name . ' ' . $model->first_name . ' ' . $model->patron_name .  ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE ? ' <span style="color:red;font-weight:normal;">[Редактирование]</span>' : null ) ?></span>
                            <?php if ($model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE): ?>
                                <span>
                                    <?= Html::button('Редактировать', [
                                        'class' => 'btn btn-default',
                                        'style' => 'padding: 3px 6px;',
                                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_PARTICIPANT_UPDATE . '\', \'page\', \'' . $model->last_name . ' ' . $model->first_name . ' ' . $model->patron_name . '\',\'' . $model->id . '\')'
                                    ]) ?>
                                </span>

                                <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-margin-bottom-10">
                        <?= $form->field($model, 'last_name', ['options' => ['class' => 'col-xs-10']])
                            ->textInput([
                                'placeholder' => 'Фамилия',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE || ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE && !Yii::$app->user->can(User::ROLE_ADMIN) && !Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR))  ? true : false
                            ])
                            ->label('<span style="color: red;">*</span> Фамилия') ?>

                        <?= $form->field($model, 'first_name', ['options' => ['class' => 'col-xs-10']])
                            ->textInput([
                                'placeholder' => 'Имя',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE || ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE && !Yii::$app->user->can(User::ROLE_ADMIN) && !Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR))  ? true : false
                            ])
                            ->label('<span style="color: red;">*</span> Имя') ?>

                        <?= $form->field($model, 'patron_name', ['options' => ['class' => 'col-xs-10 col-margin-bottom-10']])
                            ->textInput([
                                'placeholder' => 'Отчество',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE || ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE && !Yii::$app->user->can(User::ROLE_ADMIN) && !Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR))  ? true : false
                            ])
                            ->label('<span style="color: red;">*</span> Отчество') ?>

                        <?= $form->field($model, 'email', ['options' => ['class' => 'col-xs-10']])
                            ->textInput([
                                'placeholder' => 'Эл.почта',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false
                            ]) ?>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-margin-bottom-10">
                        <?= $form->field($model, 'organization', ['options' => ['class' => 'col-xs-10 col-sm-5']])
                            ->textInput([
                                'placeholder' => 'Организация',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false
                            ]) ?>

                        <?= $form->field($model, 'organization_branch', ['options' => ['class' => 'col-xs-10 col-sm-5']])
                            ->textInput([
                                'placeholder' => 'Филиал',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false
                            ]) ?>

                        <?= $form->field($model, 'post', ['options' => ['class' => 'col-xs-10']])
                            ->textInput([
                                'placeholder' => 'Должность',
                                'readonly'    => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false
                            ]) ?>

                        <?= $form->field($model, 'speciality', ['options' => ['class' => 'col-xs-10']])
                            ->textInput([
                                'placeholder' => 'Специализация',
                                'readonly' => $model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE ? true : false
                            ]) ?>
                    </div>
                </div>

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER || ($model->scenario == UserForm::SCENARIO_UPDATE && (Yii::$app->user->id == $model->id || Yii::$app->user->can(User::ROLE_ADMIN)))):
                    echo $form->field($model, 'password', ['options' => ['class' => 'col-xs-12 col-sm-6']])
                        ->textInput([
                            'placeholder' => 'Пароль'
                        ])
                        ->label(($model->scenario == UserForm::SCENARIO_REGISTER ? '<span style="color: red;">*</span> ' : null) . 'Пароль');
                endif; ?>

                <div class="col-margin-top-15 col-xs-12">
                    <span style="color: red;">*</span> - поля обязательные для заполнения

                    <hr>
                </div>

                <?php if (Yii::$app->user->can(User::ROLE_ADMIN) && $model->scenario == UserForm::SCENARIO_CREATE_PAGE):
                    echo $form->field($model, 'role', ['options' => ['class' => 'col-xs-12 col-margin-bottom-10']])
                        ->dropDownList(User::$roleList);
                endif; ?>

                <?php if ((Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)) && ($model->scenario != UserForm::SCENARIO_UPDATE && $model->scenario != UserForm::SCENARIO_PARTICIPANT_UPDATE)):
                    echo $form->field($model, 'conference', ['options' => ['class' => 'col-xs-12']])
                        ->dropDownList($conference ? [$conference->id => $conference->title] : [], (!$conference || (Yii::$app->user->can(User::ROLE_ADMIN) && $model->scenario == UserForm::SCENARIO_CREATE_PAGE)) ? ['prompt' => $model->scenario == UserForm::SCENARIO_CREATE_PAGE ? 'Без регистрации на мероприятие' : 'Регистрация закрыта'] : []);

                    echo '<div class="col-xs-12"><hr></div>';
                endif; ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton($model->scenario == UserForm::SCENARIO_UPDATE || $model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE ? 'Сохранить' : 'Зарегистрировать' , [
                    'class' => 'col-xs-12 col-sm-6 col-md-5 btn btn-success col-margin-bottom-10',
                    'name'  => 'signup',
                    'value' => 'conference',
                    'onclick' => ($model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE || $model->scenario == UserForm::SCENARIO_CREATE_PAGE) ? 'participantCountReload()' : null,
                    'form'    => ($model->scenario == UserForm::SCENARIO_UPDATE || $model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE) ? 'update-form' : 'signup-form'
                ]); ?>

                <?php if ($model->scenario == UserForm::SCENARIO_PARTICIPANT_UPDATE):
                    echo Html::button('Отмена', [
                        'class' => 'col-xs-12 col-sm-6 col-md-3 pull-right btn btn-danger col-margin-bottom-10',
                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE . '\', \'' . UserForm::LOAD_FORM_TO_PAGE . '\', \' \', ' . $model->id . ')'
                    ]);
                endif; ?>

                <?php if ($model->scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE):
                    echo Html::button('Очистить форму', [
                        'class' => 'col-xs-12 col-sm-6 col-md-3 pull-right btn btn-danger col-margin-bottom-10',
                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE_PAGE . '&clearForm=' . true . '\', \'' . UserForm::LOAD_FORM_TO_PAGE . '\')'
                    ]);
                endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php Pjax::end(); ?>
