<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini">' . Html::img('/image/department_logo_mini.png', ['class' => 'img-responsive']) . '</span><span class="logo-lg">' . Html::img('/image/department_logo.png', ['class' => 'img-responsive']) . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <?php if (Yii::$app->user->identity): ?>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="user user-menu">
                    <a href="#" data-toggle="control-sidebar">
                        <span><?= Yii::$app->user->identity->first_name; ?> <?= Yii::$app->user->identity->last_name; ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
</header>
