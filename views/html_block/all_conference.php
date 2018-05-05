<?php

/* @var $this \yii\web\View */
/* @var $limit int */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\Conference;

$conferences = new ActiveDataProvider([
    'query' => Conference::find()
        ->where(['status' => 10])
        ->limit($limit)
        ->orderBy(['start_time' => SORT_ASC]),
    'pagination' => false,
]);

?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Ближайшие конференции</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?php try {
            echo GridView::widget([
                'dataProvider' => $conferences,
                'layout' => '{items}',
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
        } ?>
    </div>
</div>
