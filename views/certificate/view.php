<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\entities\User;
use app\forms\UserForm;

/* @var $this yii\web\View */
/* @var $model app\entities\Certificate */

$this->title = $model->id;

?>

<div class="certificate-view">

    <p class="control-panel">
        <?php if (Yii::$app->user->can(User::ROLE_ADMIN)): ?>
            <?= Html::button('Редактировать', [
                'class' => 'btn btn-primary',
                'onclick' => 'formLoad(\'/certificate/update-form\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'' . $model->conference->title . '\', \'' . $model->id . '\')'
            ]); ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>

        <?= Html::a('Скачать сертификат в формате <strong>PDF</strong>', ['/certificate/download', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'target' => '_blank'
        ]) ?>
    </p>

    <?php try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'userFullName',
                'conference.title',
                'date_issue:date',
                'document_series',
                'verification_code',
                'participantMethod',
            ],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    } ?>

</div>
