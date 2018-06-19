<?php

use yii\helpers\Html;
use app\forms\UserForm;

/* @var $this \yii\web\View */

?>

<aside class="control-sidebar control-sidebar-light">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#home-tab" data-toggle="tab"><i class="fa fa-home"></i>Конференции</a></li>
        <li><a href="#certificate-tab" data-toggle="tab"><i class="fa fa-file"></i>Мои сертификаты</a></li>
        <li><a href="#setting-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <div class="tab-content">
        <div id="home-tab" class="tab-pane active fade in">

            <?= $this->render(
                '/html_block/conference_wishlist'
            ) ?>

        </div>

        <div id="certificate-tab" class="tab-pane fade">

            <?= $this->render(
                '/html_block/certificate_user'
            ) ?>

        </div>

        <div id="setting-tab" class="tab-pane fade">
            <ul class="list-unstyled">
                <li class="col-margin-bottom-10">
                    <?= Html::a('Настройки профиля', ['#'], [
                        'class' => 'btn btn-default btn-flat',
                        'data-toggle' => 'modal',
                        'data-target' => '#modalForm',
                        'onclick' => 'formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_UPDATE . '\', \'' . Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name . '\', \'' . Yii::$app->user->identity->id .'\')',
                    ]) ?>
                </li>
                <li class="col-margin-bottom-10">
                    <?= Html::a('Выйти', ['/user/logout'], [
                            'data-method' => 'post',
                            'class' => 'btn btn-default btn-flat'
                        ]
                    ); ?>
                </li>
            </ul>
        </div>
    </div>
</aside>
<div class='control-sidebar-bg'></div>
