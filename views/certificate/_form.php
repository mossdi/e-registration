<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\entities\Certificate;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\entities\Certificate */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="certificate-form">

    <?php $form = ActiveForm::begin([
        'id' => 'certificate-form',
        'action' => ['/certificate/update?id=' . $model->id],
        'validationUrl' => ['/certificate/form-validate'],
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label}\n{input}",
        ],
    ]); ?>

    <?= $form->field($model, 'date_issue')
        ->widget(DatePicker::className(), [
            'name' => 'date_issue',
            'convertFormat' => false,
            'readonly' => true,
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'placeholder' => 'Выберите дату'
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'todayHighlight' => true,
                'todayBtn' => true,
            ]
        ])?>

    <?= $form->field($model, 'document_series')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verification_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Certificate::$statusList) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
