<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\entities\Conference;
use app\entities\ConferenceSearch;
use yii2mod\alert\Alert;
use app\forms\UserForm;

/* @var $this \yii\web\View */
/* @var $searchModel app\entities\ConferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$searchModel = new ConferenceSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

$dataProvider->query->where(['is not', 'end_time', null])->andWhere(['status' => Conference::STATUS_ACTIVE]);

//TODO: Переделать!!!!!!!!!!

?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Посещённые конференции</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'id' => 'historyConferenceContainer',
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
                                'onclick' => 'formLoad(\'/conference/view\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'' . $model->title . '\', \'' . $model->id . '\')']
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

        <?php Pjax::end() ?>
    </div>
</div>
