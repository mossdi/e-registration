<?php

/* @var $this yii\web\View */

$this->title = 'Департамент здравоохранения';

?>

<div class="site-index">

    <?= $this->render(
        '/html_block/future_conference'
    ) ?>

    <?= $this->render(
        '/html_block/wishlist_conference'
    ) ?>

</div>
