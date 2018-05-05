<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\entities\ConferenceSearch */

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->title, ['/#'], [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick' => 'formLoad(\'/conference/view\', \'' . $model->title . '\', \'' . $model->id . '\')']
                    );
                }
            ],

            'start_time:datetime',

            [
                'label' => 'Участники конференции',
                'value' => function($model) {
                    return $model->conferenceUsers;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'controller' => '/conference',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('Редактировать', ['/#'], [
                                'data-toggle' => 'modal',
                                'data-target' => '#modalForm',
                                'onclick' => 'formLoad(\'/conference/update\', \'' . $model->title . '\', \'' . $model->id . '\')']
                        );
                    }
                ],
            ],
        ],
    ]);
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "\n";
}
