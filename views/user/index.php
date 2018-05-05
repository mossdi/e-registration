<?php

/* @var $searchModel \app\entities\UserSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $this \yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'last_name',
            'first_name',
            'patron_name',
            'organization',
            'post',
            'passport',
            'email:email',
            'phone',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 10 ? 'Включен' : 'Заблокирован';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick' => 'formLoad(\'/user/update-form\', \'' . $model->last_name . ' ' . $model->first_name . ' ' . $model->patron_name . '\',\'' . $model->id . '\')'
                        ]);
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}
