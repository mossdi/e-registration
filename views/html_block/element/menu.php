<?php

/* @var $this \yii\web\View */
/* @var $conference_current \app\entities\Conference; */

use app\widgets\Menu;
use app\forms\UserForm;
use app\entities\User;
use app\entities\ConferenceParticipant;
use app\components\ConferenceComponent;

$conference_current = ConferenceComponent::conferenceCurrent();

try {
    echo Menu::widget(
        [
            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
            'items' => [
                [
                    'label' => 'Меню',
                    'options' => [
                        'class' => 'header'
                    ]
                ],

                [
                    'label' => 'Текущая конференция',
                    'icon' => 'bullhorn fa-2x',
                    'url' => '/conference/current',
                    'visible' => $conference_current && (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR) || (Yii::$app->user->can(User::ROLE_PARTICIPANT) && ConferenceParticipant::findOne(['conference_id' => $conference_current->id, 'user_id' => Yii::$app->user->id]))) ? true : false,
                ],

                [
                    'label' => 'Страница регистрации',
                    'icon' => 'address-book fa-2x',
                    'url' => '#',
                    'template' => '<a href=\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE_PAGE . '\'>{icon}{label}</a>',
                    'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR),
                ],

                [
                    'label' => 'Новая конференция',
                    'icon' => 'plus-square fa-2x',
                    'url' => '#',
                    'template' => '<a href="#" data-toggle="modal" data-target="#modalForm" onclick="formLoad(\'/conference/create-form\', \'' . UserForm::LOAD_FORM_TO_MODAL . '\', \'Новая конференция\')">{icon}{label}</a>',
                    'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SPEAKER),
                ],

                [
                    'label' => 'Администрирование',
                    'icon' => 'edit fa-2x',
                    'options' => [
                        'class' => ''
                    ],
                    'items' => [
                        [
                            'label' => 'Пользователи',
                            'icon' => 'address-book',
                            'url' => ['/user'],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                        ],
                        [
                            'label' => 'Конференции',
                            'icon' => 'mixcloud',
                            'url' => ['/conference'],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                        ],
                        [
                            'label' => 'Сертификаты',
                            'icon' => 'file',
                            'url' => ['/certificate'],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                        ],
                    ]
                ],

                [
                    'label' => 'Настройки',
                    'icon' => 'cogs fa-2x',
                    'url' => ['/setting/index'],
                    'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                ],
            ],
        ]
    );
} catch (Exception $e) {
    echo $e->getMessage();
}
