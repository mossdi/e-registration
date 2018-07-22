<?php

/* @var $this \yii\web\View */
/* @var $participants \yii\data\ActiveDataProvider */
/* @var $conference \app\entities\Conference */

use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\User;
use app\entities\Conference;
use yii2mod\alert\Alert;
use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'participantsListContainer',
    'enablePushState' => false,
]);

try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}

echo Html::button('Обновить список участников <span style="margin-left: 5px;" class="glyphicon glyphicon-refresh"></span>', [
    'class' => 'btn btn-default col-margin-bottom-10',
    'onclick' => 'formLoad(\'/conference/participant\', \'modal\', \'Участники конференции\', \'' . $conference->id . '\')'
]);

try {
    echo GridView::widget([
        'dataProvider' => $participants,
        'layout' => '{items}{pager}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Ф.И.О.',
                'value' => function($model) {
                    return $model->user->last_name . ' ' . $model->user->first_name . ' ' . $model->user->patron_name;
                }
            ],
            [
                'label' => 'Организация',
                'attribute' => 'user.organization',
            ],
            [
                'label' => 'Должность',
                'attribute' => 'user.post',
            ],

            [
                'label' => 'Присутствие',
                'attribute' => 'method',
                'value' => function($model) {
                    return $model->method == Conference::LEARNING_FULL_TIME ? 'Очно' : 'Дистанционно';
                },
                'enableSorting' => false,
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Yii::$app->user->can(User::ROLE_ADMIN) && $conference->end_time == null ? '{delete}' : '<i class="fa fa-check-circle"></i>',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::button('Удалить', [
                            'onclick' => 'deleteParticipant(' . $model->user_id . ', ' . $model->conference_id . ', \'' . $model->conference->title . '\')',
                            'class'   => 'btn',
                            'data-confirm' => 'Вы уверены, что хотите удалить пользователя с конференции?',
                        ]);
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}

Pjax::end();
