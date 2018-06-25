<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\entities\Conference;
use app\entities\CertificateSearch;
use app\entities\Certificate;

/* @var $this yii\web\View */
/* @var $searchModel app\entities\CertificateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$searchModel = new CertificateSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

$dataProvider->query->where(['user_id' => Yii::$app->user->id])->andWhere(['certificate.status' => Certificate::STATUS_ACTIVE]);

?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Список сертификатов</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin([
            'id' => 'userCertificateListContainer',
            'enablePushState' => false,
        ]) ?>

        <?php try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'conference.title',
                        'filter' => $dataProvider->count > 0 ? Html::activeDropDownList($searchModel, 'conference_id',
                            ArrayHelper::map(Conference::find()
                                ->all(), 'id', 'title'
                            ),  ['prompt' => 'Все']
                        ) : false
                    ],
                    [

                        'label' =>  'Дата проведения конференции',
                        'attribute' => 'conference.start_time',
                        'format' => 'date',
                    ],
                    'document_series',
                    [
                        'attribute' => 'date_issue',
                        'format' => 'date',
                        'filter' => false
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/certificate/view\', \'' . $model->conference->title . '\', \'' . $model->id . '\')'
                                ]);
                            },
                        ]
                    ],
                ],
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>

        <?php Pjax::end() ?>
    </div>
</div>
