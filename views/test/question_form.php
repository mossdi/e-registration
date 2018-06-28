<?php

/* @var $model \app\entities\Question */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([]); ?>

<?= $form->field($model, 'question')->textInput() ?>

<?= $form->field($model, 'sort_order')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton(!empty($model->id) ? 'Сохранить' : 'Создать', [
        'class' => 'btn btn-default',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>