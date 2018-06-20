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

    <?php try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'userFullName',
                'conference.title',
                'date_issue:date',
                'document_series',
                'participantMethod',
            ],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    } ?>

</div>
