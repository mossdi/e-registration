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
        ->joinWith(['participant'])
           ->where(['is not', 'end_time', null])
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
    'id' => 'historyConferenceContainer',
    'enablePushState' => false,
]) ?>

<?php try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
} ?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Посещённые конференции</h3>
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
                ],
            ]);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        } ?>

    </div>
</div>

<?php Pjax::end() ?>
