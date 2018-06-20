<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\entities\Conference;
use yii2mod\alert\Alert;

/* @var $this \yii\web\View */
/* @var $limit int */

$conferences = new ActiveDataProvider([
    'query' => Conference::find()
            ->with(['wishList', 'author', 'participant', 'wishList'])
           ->where(['>', '(start_time + ' . Yii::$app->setting->get('registerClose') . ')', time()])
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

<?php Pjax::begin([
    'id' => 'futureConferenceContainer',
    'enablePushState' => false,
]) ?>

<?php try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
} ?>

<div class="box">
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
                                return !$model->participant && $model->registerTime ? Html::a('Участвовать', ['/user/register-participant?user_id=' . Yii::$app->user->id . '&conference_id=' . $model->id . '&method=' . Conference::LEARNING_DISTANCE],
                                    ['data-pjax' => true]
                                ) . ' / ' : '';
                            },
                            'whishlist' => function ($url, $model) {
                                return !$model->wishList ? Html::a('<span class= "glyphicon glyphicon-star-empty"></span>', ['/conference/add-to-wish-list?id=' . $model->id . '&from=' . basename(__FILE__)],
                                    ['data' => ['toggle' => 'tooltip', 'pjax' => true], 'title' => 'В избранное', 'onclick' => 'wishListReload()']
                                ) : Html::a('<span class= "glyphicon glyphicon-star"></span>', ['/conference/delete-from-wish-list?id=' . $model->id . '&from=' . basename(__FILE__)],
                                    ['data' => ['toggle' => 'tooltip', 'pjax' => true], 'title' => 'Удалить из избранного', 'onclick' => 'wishListReload()']
                                );
                            },
                        ],
                    ],
                ],
            ]);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        } ?>

    </div>
</div>

<?php Pjax::end() ?>
