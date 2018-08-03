<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use app\entities\ConferenceParticipantSearch;
use yii2mod\alert\Alert;
use yii\helpers\Html;
use app\forms\UserForm;

/* @var $this \yii\web\View */
/* @var $searchModel app\entities\ConferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$searchModel = new ConferenceParticipantSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

$dataProvider->query->where(['conference_participant.user_id' => Yii::$app->user->id]);

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

                    'conference.title',
                    'conference.start_time:datetime',
                    [
                        'attribute' => 'certificateVerificationCode',
                        'label' => 'Сертификат',
                        'value' => function($model) {
                            return $model->certificateVerificationCode;
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['#'], [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/certificate/view\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'' . $model->conference->title . '\', \'' . $model->certificate->id . '\')'
                                ]);
                            },
                        ]
                    ],
                ],
            ]);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        } ?>

        <?php Pjax::end() ?>
    </div>
</div>
