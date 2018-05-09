<?php

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\entities\UserSearch */

use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\User;
use app\forms\UserForm;

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
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', User::$statusList)
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_UPDATE . '\', \'' . $model->last_name . ' ' . $model->first_name . ' ' . $model->patron_name . '\',\'' . $model->id . '\')'
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'data-method'  => 'post',
                            'data-confirm' => 'Вы уверены, что хотите удалить пользователя?',
                        ]);
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}
