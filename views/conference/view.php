<?php

/* @var $this \yii\web\View */
/* @var $model \app\entities\Conference */

use yii\widgets\DetailView;

?>

<div class="conference-view">
    <?php try {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',

                /*[
                    'attribute' => 'author_id',
                    'label' => 'Ведущий',
                    'value' => function($model) {
                        return $model->author->first_name . ' ' . $model->author->last_name;
                    }
                ],*/

                [
                    'attribute' => 'description',
                    'label' => 'Описание',
                    'format' => 'html',
                ],
                'start_time:datetime',
            ],
        ]);
    } catch (Exception $e) {
        echo 'Выброшено исключение: ', $e->getMessage(), "\n";
    } ?>
</div>
