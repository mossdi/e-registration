<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\forms\UserForm;

/* @var $this yii\web\View */
/* @var $searchModel app\entities\CertificateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataProvider->pagination->pageSize = 50;

$this->title = 'Сертификаты';

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
            'id' => 'adminCertificateListContainer',
            'enablePushState' => false,
        ]) ?>

        <?php $certificateForm = Yii::$app->request->get('form') ?>

        <ul class="nav nav-pills col-margin-bottom-10">
            <li class="<?= !$certificateForm ? 'active' : '' ?>"><?= Html::a('Все', ['/certificate/index']) ?></li>
            <li class="<?= $certificateForm == 'ready' ? 'active' : '' ?>"><?= Html::a('Готовые', ['/certificate/index?form=ready']) ?></li>
            <li class="<?= $certificateForm == 'empty' ? 'active' : '' ?>"><?= Html::a('Пустые', ['/certificate/index?form=empty']) ?></li>
        </ul>

        <?php try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'user.last_name',
                        'filter' => Html::activeInput('text', $searchModel, 'userLastName', ['class' => 'form-control'])
                    ],
                    [
                        'attribute' => 'user.first_name',
                        'filter' => Html::activeInput('text', $searchModel, 'userFirstName', ['class' => 'form-control'])
                    ],
                    [
                        'attribute' => 'user.patron_name',
                        'filter' => Html::activeInput('text', $searchModel, 'userPatronName', ['class' => 'form-control'])
                    ],
                    [
                        'attribute' => 'conference.title',
                    ],
                    [

                        'label' =>  'Дата проведения конференции',
                        'attribute' => 'conference.start_time',
                        'format' => 'date',
                    ],
                    'document_series',
                    'verification_code',
                    [
                        'attribute' => 'date_issue',
                        'format' => 'date',
                        'filter' => false,
                        'contentOptions' => [
                            'nowrap' => true
                        ]
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'contentOptions' => [
                            'nowrap' => true
                        ],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/certificate/view\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'' . $model->conference->title . '\', \'' . $model->id . '\')'
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/certificate/update-form\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'' . $model->conference->title . '\', \'' . $model->id . '\')'
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
