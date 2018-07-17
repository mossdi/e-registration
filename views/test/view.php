<?php

/* @var $this \yii\web\View */
/* @var $conference \app\entities\Conference */
/* @var $questions \yii\db\ActiveRecord */
/* @var $question \app\entities\Question */
/* @var $answer \app\entities\Answer */

use yii\helpers\Html;
use app\entities\Answer;

$this->title = $conference->title;

?>

<h1>Опросник</h1>

<hr>

<?php if (!empty($questions)): ?>
    <table class="table table-bordered table-responsive">
        <?php foreach ($questions as $question): ?>
            <tr class="col-question">
                <td class="text-center">
                    <strong><?= $question->sort_order ?></strong>
                </td>
                <td>
                    <strong><?= $question->question; ?></strong>
                </td>
                <td>
                    <?= Html::a('Добавить ответ', ['/#'], [
                        'class' => 'btn btn-default',
                        'data-toggle' => 'modal',
                        'data-target' => '#modalForm',
                        'onclick'     => 'formLoad(\'/test/add-answer\', \'modal\', \'Новый ответ\', \'' . $question->id . '\')'
                    ]) ?>
                </td>
                <td>
                    <?= Html::a('Редактировать', ['/#'], [
                        'class' => 'btn btn-default',
                        'data-toggle' => 'modal',
                        'data-target' => '#modalForm',
                        'onclick'     => 'formLoad(\'/test/update-question\', \'modal\', \'' . $question->question . '\', \'' . $question->id . '\')'
                    ]) ?>

                    <?= Html::a('Удалить', ['/test/delete-question?id=' .  $question->id], [
                        'class' => 'btn btn-danger',
                        'data-confirm' => 'Вы уверены, что хотите удалить вопрос?',
                    ]) ?>
                </td>
            </tr>
            <?php foreach (Answer::findAll(['question_id' => $question->id]) as $answer): ?>
                <tr class="col-answer">
                    <td class="text-right">-</td>
                    <td><?= $answer->answer ?></td>
                    <td><?= $answer->correctness ? '<i class="fa fa-check"></i>' : '' ?></td>
                    <td>
                        <?= Html::a('Редактировать', ['/#'], [
                            'class' => 'btn',
                            'data-toggle' => 'modal',
                            'data-target' => '#modalForm',
                            'onclick'     => 'formLoad(\'/test/update-answer\', \'modal\', \'' . $answer->answer . '\', \'' . $answer->id . '\')'
                        ]) ?>

                        <?= Html::a('Удалить', ['/test/delete-answer?id=' .  $answer->id], [
                            'class' => 'btn',
                            'data-confirm' => 'Вы уверены, что хотите удалить вопрос?',
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
<?php endif ?>

<?= Html::a('Добавить вопрос', ['/#'], [
    'class' => 'btn btn-info',
    'data-toggle' => 'modal',
    'data-target' => '#modalForm',
    'onclick'     => 'formLoad(\'/test/add-question\', \'modal\', \'Новый вопрос\', \'' . $conference->id . '\')'
]) ?>
