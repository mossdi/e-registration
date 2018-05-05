<?php

/* @var $this yii\web\View */
/* @var $model app\entities\Conference */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use vova07\imperavi\Widget as Imperavi;

?>

<div class="conference-form">
    <?php $form = ActiveForm::begin([
        'id' => 'conference-form',
        'action' => ['/conference/conference-create'],
        'validationUrl' => ['/conference/conference-form-validate'],
        'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label}\n{input}",
        ],
    ]); ?>

    <?= $form->field($model, 'start_time')
        ->widget(DateTimePicker::className(), [
            'name' => 'start_time',
            'convertFormat' => false,
            'readonly' => true,
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'placeholder' => 'Выберите дату и время начала конференции'
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yy hh:ii',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'title')
        ->textInput(['placeholder' => 'Название конференции', 'maxlength' => true]) ?>

    <?= $form->field($model, 'description')
        ->widget(Imperavi::className(),[
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 200,
                'plugins' => [
                    'clips',
                    'fullscreen',
                ],
            ],
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', [
            'class' => 'btn btn-default',
            'name' => 'conference-create-button',
            'form' => 'conference-form',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
