<?php

/* @var $this \yii\web\View */
/* @var $participants \yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\User;
use app\entities\Conference;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'participantsListContainer']);

try {
    echo GridView::widget([
        'dataProvider' => $participants,
        'layout' => '{items}',
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
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Yii::$app->user->can(User::ROLE_ADMIN) ? Html::a('Удалить', ['/conference/delete-participant?user_id=' . $model->user_id . '&conference_id=' . $model->conference_id], [
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить пользователя с конференции?',
                                'method'  => 'post',
                            ]
                        ]) : null;
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}

Pjax::end();
