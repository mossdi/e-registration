<?php

/* @var $conference \app\entities\Conference */
/* @var $this \yii\web\View */

use yii\helpers\Html;

$this->title = $conference->title;

?>

<?= Html::a('Добавить вопрос', ['/#'], [
    'class' => 'btn btn-info',
    'data-toggle' => 'modal',
    'data-target' => '#modalForm',
    'onclick'     => 'formLoad(\'/test/add-question-form\', \'Новый вопрос\', \'' . $conference->id . '\')'
]) ?>
