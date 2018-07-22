<?php

/* @var $this \yii\web\View */
/* @var $conference_now \app\entities\Conference|\yii\db\ActiveRecord */

?>

<aside class="main-sidebar">
    <section class="sidebar">

        <?= $this->render(
            '/html_block/element/menu', [
                'conference_now' => $conference_now
            ]
        ) ?>

    </section>
</aside>
