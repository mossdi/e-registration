<?php

/* @var $this \yii\web\View */
/* @var $model \app\entities\Conference */

use app\entities\User;
use yii\helpers\Html;

?>

<?php if (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SPEAKER)):  ?>
<div class="control-panel">
    <?= Html::a('Закрыть конференцию', ['#'], ['class' => 'btn btn-danger']) ?>
</div>
<?php endif; ?>

<hr>

<h1><?= $model->title ?></h1>

<hr>

<div class="description">
    <?= $model->description ?>
</div>