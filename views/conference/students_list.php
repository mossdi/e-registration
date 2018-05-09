<?php

/* @var $this \yii\web\View */
/* @var $students \yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

try {
    echo GridView::widget([
        'dataProvider' => $students,
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
                'value' => function($model) {
                    return $model->user->organization;
                }
            ],
            [
                'label' => 'Должность',
                'value' => function($model) {
                    return $model->user->post;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('Удалить', ['/conference/delete-user-to-conference?user_id=' . $model->user_id . '&conference_id=' . $model->conference_id], [
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ]
                        ]);
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}
