<?php

/* @var $model \app\entities\Answer */
/* @var $this \yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([]); ?>

<?= $form->field($model, 'answer')->textInput() ?>

<?= $form->field($model, 'correctness')->checkbox() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', [
        'class' => 'btn btn-default',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>