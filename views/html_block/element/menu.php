<?php

/* @var $this \yii\web\View */

use app\widgets\Menu;
use app\forms\UserForm;
use app\entities\User;

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
                    'label' => 'Регистрация',
                    'icon' => 'bell',
                    'items' => [
                        [
                            'label' => 'Пользователь',
                            'icon' => 'address-book',
                            'url' => '#',
                            'template' => '<a href="#" data-toggle="modal" data-target="#modalForm" onclick="formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE . '\', \'Регистрационная карточка\')">{icon}{label}</a>',
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST),
                        ],
                        [
                            'label' => 'Конференция',
                            'icon' => 'mixcloud',
                            'url' => '#',
                            'template' => '<a href="#" data-toggle="modal" data-target="#modalForm" onclick="formLoad(\'/conference/create-form\', \'Новая конференция\')">{icon}{label}</a>',
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                        ],
                    ]
                ],
                [
                    'label' => 'Администрирование',
                    'icon' => 'cogs',
                    'options' => [
                        'class' => ''
                    ],
                    'items' => [
                        [
                            'label' => 'Пользователи',
                            'icon' => 'address-book',
                            'url' => ['/user/index'],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN)
                        ],
                        [
                            'label' => 'Конференции',
                            'icon' => 'address-book',
                            'url' => ['/conference/index'],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST)
                        ],
                    ]
                ],
            ],
        ]
    );
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}
