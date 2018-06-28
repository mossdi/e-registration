<?php

/* @var $this \yii\web\View */
/* @var $conference \app\entities\Conference */
/* @var $questions \yii\db\ActiveRecord */
/* @var $question \app\entities\Question */

use yii\helpers\Html;

$this->title = $conference->title;

?>

<?php if (!empty($questions)): ?>
    <table class="table table-bordered table-responsive">
        <?php foreach ($questions as $question): ?>
            <tr>
                <td>
                    <?= $question->sort_order ?>
                </td>
                <td colspan="2">
                    <strong><?= $question->question; ?></strong>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif ?>

<?= Html::a('Добавить вопрос', ['/#'], [
    'class' => 'btn btn-info',
    'data-toggle' => 'modal',
    'data-target' => '#modalForm',
    'onclick'     => 'formLoad(\'/test/add-question\', \'Новый вопрос\', \'' . $conference->id . '\')'
]) ?>
