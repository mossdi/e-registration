<?php

/* @var $this \yii\web\View */
/* @var $limit int */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use app\entities\ConferenceWishlist;
use yii\widgets\Pjax;
use yii2mod\alert\Alert;

$conferences = new ActiveDataProvider([
    'query' => ConferenceWishlist::find()
        ->joinWith(['conference'])
           ->where(['user_id' => Yii::$app->user->id]),
    'sort' => [
        'attributes' => [
            'conference.title' => [
                'asc'  => ['title' => SORT_ASC],
                'desc' => ['title' => SORT_DESC],
            ],
            'conference.start_time' => [
                'asc'  => ['start_time' => SORT_ASC],
                'desc' => ['start_time' => SORT_DESC],
            ]
        ],
        'defaultOrder' => [
            'conference.start_time' => SORT_ASC,
        ]
    ],
    'pagination' => [
        'pageSize' => 10,
    ],
]);

?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Избранные конференции</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'id' => 'wishListContainer',
            'enablePushState' => false,
        ]) ?>

        <?php try {
            echo Alert::widget();
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        } ?>

        <?php try {
            echo GridView::widget([
                'dataProvider' => $conferences,
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

                    [
                        'attribute' => 'conference.author_id',
                        'label' => 'Ведущий',
                        'value' => function($model) {
                            return $model->conference->author->first_name . ' ' . $model->conference->author->last_name;
                        }
                    ],

                    'conference.start_time:datetime',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'controller' => '/conference',
                        'buttons' => [
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                    ['conference/delete-from-wish-list?id=' . $model->conference_id . '&from=' . basename(__FILE__)],
                                    ['data' => ['toggle' => 'tooltip', 'pjax' => true], 'title' => 'Удалить', 'onclick' => 'futureConferenceReload()']
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
