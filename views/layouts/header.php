<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $conference_current \app\entities\Conference|\yii\db\ActiveRecord */

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\entities\User;
use app\services\conference\ConferenceService;

$conference_current = ConferenceService::conferenceCurrent();

?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini">' . Html::img('/image/niiozmm_logo_mini.png', ['class' => 'img-responsive']) . '</span><span class="logo-lg">' . Html::img('/image/niiozmm_logo.png', ['class' => 'img-responsive']) . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <?php Pjax::begin([
            'id' => 'participantCountContainer',
            'enablePushState' => false,
        ]) ?>
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <?php if ($conference_current): ?>
            <span style="display: inline-block; padding: 15px 10px;">
                <strong class="hidden-xs hidden-sm">Всего участников:</strong> <?= $conference_current->participantCount ?>
            </span>
            <?= Html::button('<span class="glyphicon glyphicon-refresh"></span>', [
                'onclick' => 'participantCountReload()',
                'class' => 'btn'
            ]); ?>
            <span style="display: inline-block; padding: 15px 10px;">
                <strong class="hidden-xs hidden-sm">Зарег-но на данной стойке:</strong> <?= $conference_current->reseptionParticipantCount ?>
            </span>
        <?php endif; ?>

        <?php if (Yii::$app->user->identity): ?>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if (Yii::$app->user->can(User::ROLE_RECEPTIONIST) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)): ?>
                        <li class="hidden-xs hidden-sm"><span class="item"><?= Yii::$app->user->identity->first_name ?></span></li>
                    <?php endif; ?>
                    <li class="user user-menu">
                        <?php if (Yii::$app->user->can(User::ROLE_PARTICIPANT) || Yii::$app->user->can(User::ROLE_ADMIN)): ?>
                            <a href="#" data-toggle="control-sidebar">
                                <span><?= Yii::$app->user->identity->first_name; ?> <?= Yii::$app->user->identity->last_name; ?></span>
                            </a>
                        <?php else:; ?>
                            <?= Html::a('Выйти', ['/user/logout'], [
                                'data-method' => 'post',
                                'data-confirm' => 'Хотите выйти?'
                            ]); ?>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <?php Pjax::end(); ?>
    </nav>
</header>
