<?php

/* @var $settings \app\entities\Setting */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii2mod\alert\Alert;

?>

<?php Pjax::begin([
    'enablePushState' => false,
]) ?>

<?php try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
} ?>

<?php $form = ActiveForm::begin([
    'action' => ['/setting/update'],
    'options' => [
        'data-pjax' => true,
    ]
]); ?>

<?php foreach ($settings as $index => $setting) {
    echo $form->field($setting, "[$index]value")->label($setting->label);
} ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', [
        'class' => 'btn btn-default',
    ]) ?>
</div>

<?php ActiveForm::end() ?>

<?php Pjax::end() ?>




