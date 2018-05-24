<?php

/* @var $this \yii\web\View */
/* @var $model \app\entities\Conference */

use app\entities\User;
use yii\helpers\Html;

?>

<?php if (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SPEAKER)):  ?>
<div class="control-panel">
    <?= Html::a('Список участников', ['/#'], [
        'class' => 'btn btn-info',
        'data-toggle' => 'modal',
        'data-target' => '#modalForm',
        'onclick'     => 'formLoad(\'/conference/participant\', \'' . $model->title . '\', \'' . $model->id . '\')'
    ]) ?>
    <?= Html::a('Закрыть конференцию', ['/conference/close?id=' . $model->id], [
        'class' => 'btn btn-danger pull-right',
        'data-method' => 'post',
        'data-confirm' => 'Хотите закрыть конференцию?'
    ]) ?>
</div>
<?php endif; ?>

<hr>

<h1><?= $model->title ?></h1>

<hr>

<div class="description">
    <?= $model->description ?>
</div>