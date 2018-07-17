<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\entities\Conference;
use app\entities\ConferenceSearch;
use yii2mod\alert\Alert;

/* @var $this \yii\web\View */
/* @var $searchModel app\entities\ConferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$searchModel = new ConferenceSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

$dataProvider->query->where(['>', '(start_time + ' . Yii::$app->setting->get('registerClose') . ')', time()])
                 ->andWhere(['status' => Conference::STATUS_ACTIVE]);

?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Ближайшие конференции</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'id' => 'futureConferenceContainer',
            'enablePushState' => false,
        ]) ?>

        <?php try {
            echo Alert::widget();
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        } ?>

        <?php try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->title, ['/#'], [
                                'data-toggle' => 'modal',
                                'data-target' => '#modalForm',
                                'onclick' => 'formLoad(\'/conference/view\', \'modal\', \'' . $model->title . '\', \'' . $model->id . '\')']
                            );
                        }
                    ],

                    [
                        'attribute' => 'author_id',
                        'label' => 'Ведущий',
                        'value' => function($model) {
                            return $model->author->first_name . ' ' . $model->author->last_name;
                        }
                    ],

                    'start_time:datetime',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{register} {whishlist}',
                        'controller' => '/conference',
                        'buttons' => [
                            'register' => function ($url, $model) {
                                return !$model->participant && $model->registerTime ? Html::button('Участвовать', [
                                        'onclick' => 'registerParticipant(' . Yii::$app->user->id . ', ' . $model->id . ', \'' . Conference::LEARNING_DISTANCE . '\')',
                                        'data'    => ['toggle' => 'tooltip'],
                                        'class'   => 'btn',
                                        'title'   => 'Дистанционно',
                                    ]
                                ) : null;
                            },
                            'whishlist' => function ($url, $model) {
                                return !$model->wishList ? Html::button('<span class= "glyphicon glyphicon-star-empty"></span>', [
                                        'onclick' => 'addToWishList(' . $model->id . ')',
                                        'data'    => ['toggle' => 'tooltip'],
                                        'class'   => 'btn',
                                        'title'   => 'В избранное',
                                    ]
                                ) : Html::button('<span class= "glyphicon glyphicon-star"></span>', [
                                        'onclick' => 'deleteFromWishList(' . $model->id . ')',
                                        'data'    => ['toggle' => 'tooltip'],
                                        'class'   => 'btn',
                                        'title'   => 'Удалить из избранного',
                                    ]
                                );
                            },
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
