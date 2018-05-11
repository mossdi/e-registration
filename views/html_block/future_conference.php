<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\entities\Conference;

/* @var $this \yii\web\View */
/* @var $limit int */

$conferences = new ActiveDataProvider([
    'query' => Conference::find()
        ->with(['wishList'])
        ->where(['>', 'start_time', time()])
        ->andWhere(['status' => Conference::STATUS_ACTIVE]),
    'sort' => [
        'defaultOrder' => [
            'start_time' => SORT_ASC
        ]
    ],
    'pagination' => [
        'pageSize' => '10'
    ],
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
        <?php Pjax::begin(['id' => 'futureConferenceContainer']) ?>

        <?php try {
            echo GridView::widget([
                'dataProvider' => $conferences,
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
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{whishlist}',
                        'controller' => '/conference',
                        'buttons' => [
                            'whishlist' => function ($url, $model) {
                                return !$model->wishList ? Html::a('<span class= "glyphicon glyphicon-star-empty"></span>',
                                    ['/conference/conference-to-wish-list?id=' . $model->id], [
                                        'data-toggle' => 'tooltip',
                                        'title' => 'В избранное'
                                    ]) : Html::a('<span class= "glyphicon glyphicon-star"></span>',
                                    ['/conference/delete-conference-to-wish-list?id=' . $model->id], [
                                        'data-toggle' => 'tooltip',
                                        'title' => 'Удалить из избранного'
                                    ]
                                );
                            }
                        ],
                    ],
                ],
            ]);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        } ?>

        <?php Pjax::end() ?>
    </div>
</div>
