<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\forms\UserForm;

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
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?= Yii::$app->user->identity->first_name; ?> <?= Yii::$app->user->identity->last_name; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <p><?= Yii::$app->user->identity->organization; ?></p>
                            <p><?= Yii::$app->user->identity->post; ?></p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a('Настройки профиля', ['#'], [
                                    'class' => 'btn btn-default btn-flat',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalForm',
                                    'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_UPDATE . '\', \'' . Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name . '\', \'' . Yii::$app->user->identity->id .'\')',
                                ]) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('Выйти', ['/user/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
</header>
