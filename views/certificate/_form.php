<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\entities\Certificate;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\entities\Certificate */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="certificate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_issue')
        ->widget(DateTimePicker::className(), [
            'name' => 'start_time',
            'convertFormat' => false,
            'readonly' => true,
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'placeholder' => 'Укажите дату выдачи'
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yy hh:ii',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'document_series')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Certificate::$statusList) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
