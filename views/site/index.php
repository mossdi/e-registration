<?php

/* @var $this yii\web\View */

$this->title = 'Департамент здравоохранения';

?>

<div class="site-index">

    <?= $this->render('/html_block/my_conference', [
            'limit' => 10
        ]
    ) ?>

</div>
