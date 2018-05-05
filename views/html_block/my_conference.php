<?php

/* @var $this \yii\web\View */
/* @var $limit int */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\UserToConference;

$conferences = new ActiveDataProvider([
    'query' => UserToConference::find()
        ->with(['conference'])
        ->where(['user_id' => Yii::$app->user->id])
        ->limit($limit),
    'pagination' => false,
]);

?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Конференции с моим участием</h3>
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
                        'attribute' => 'conference.title',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::a($model->conference->title, ['/#'], [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/conference/view\', \'' . $model->conference->title . '\', \'' . $model->conference->id . '\')']
                            );
                        }
                    ],

                    'conference.start_time:datetime',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'controller' => '/conference',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('Смотреть', ['/#'], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalForm',
                                        'onclick' => 'formLoad(\'/conference/view\', \'' . $model->conference->title . '\', \'' . $model->conference->id . '\')']
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