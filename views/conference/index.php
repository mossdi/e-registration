<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\entities\ConferenceSearch */

$dataProvider->pagination->pageSize = INF;

?>

<?php Pjax::begin(['id' => 'conferenceListContainer']); ?>

<?php $timeParams = Yii::$app->request->get('time') ?>

<ul class="nav nav-pills">
    <li class="<?= !$timeParams ? 'active' : '' ?>"><?= Html::a('Все', ['/conference/index']) ?></li>
    <li class="<?= $timeParams == 'now' ? 'active' : '' ?>"><?= Html::a('Текущая', ['/conference/index?time=now']) ?></li>
    <li class="<?= $timeParams == 'future' ? 'active' : '' ?>"><?= Html::a('Грядущие', ['/conference/index?time=future']) ?></li>
    <li class="<?= $timeParams == 'history' ? 'active' : '' ?>"><?= Html::a('Архивные', ['/conference/index?time=history']) ?></li>
</ul>

<hr>

<?php try {
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
                            'onclick'     => 'formLoad(\'/conference/view\', \'' . $model->title . '\', \'' . $model->id . '\')']
                    );
                }
            ],
            [
                'attribute' => 'start_time',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'label' => 'Участники конференции',
                'format' => 'raw',
                'value' => function($model) {
                    return 'Количество: ' . $model->studentCount . ' / ' . Html::a('Список', ['/#'], [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick'     => 'formLoad(\'/conference/user-to-conference\', \'' . $model->title . '\', \'' . $model->id . '\')'
                        ]);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'controller' => '/conference',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/#'], [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick'     => 'formLoad(\'/conference/update-form\', \'' . $model->title . '\', \'' . $model->id . '\')'
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return !($model->start_time < time() && $model->end_time == null) ?
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'data-method'  => 'post',
                                'data-confirm' => 'Вы уверены, что хотите удалить событие?',
                            ]
                        ) : null;
                    },
                ],
            ],
        ],
    ]);
} catch (Exception $e) {
    echo 'Выброшено исключение: ', $e->getMessage(), "\n";
} ?>

<?php Pjax::end(); ?>
