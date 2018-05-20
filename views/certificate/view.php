<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\entities\User;

/* @var $this yii\web\View */
/* @var $model app\entities\Certificate */

$this->title = $model->id;

?>

<div class="certificate-view">

    <?php if (Yii::$app->user->can(User::ROLE_ADMIN)): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'conference_id',
            'date_issue',
            'document_series',
            'learning_method',
            'status',
            'deleted',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
