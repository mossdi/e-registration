<?php

/* @var $this \yii\web\View */

use app\widgets\Menu;
use app\forms\UserForm;
use app\entities\User;
use app\entities\Conference;

$conference_now = Conference::find()
       ->where(['<', 'start_time', time()])
    ->andWhere(['is', 'end_time', null])
    ->andWhere(['status' => Conference::STATUS_ACTIVE])
         ->one();

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
                    'url' => $conference_now ? '/conference/view-now?id=' . $conference_now->id : '',
                    'visible' => $conference_now ? true : false,
                ],

                [
                    'label' => 'Регистрация',
                    'icon' => 'plus-square fa-2x',
                    'items' => [
                        [
                            'label' => 'Пользователь',
                            'icon' => 'address-book',
                            'url' => '#',
                            'template' => '<a href="#" data-toggle="modal" data-target="#modalForm" onclick="formLoad(\'/user/signup-form?scenario=' . UserForm::SCENARIO_CREATE . '\', \'modal\', \'Регистрационная карточка\')">{icon}{label}</a>',
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST),
                        ],
                        [
                            'label' => 'Конференция',
                            'icon' => 'mixcloud',
                            'url' => '#',
                            'template' => '<a href="#" data-toggle="modal" data-target="#modalForm" onclick="formLoad(\'/conference/create-form\', \'modal\', \'Новая конференция\')">{icon}{label}</a>',
                            'visible' => Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SPEAKER),
                        ],
                    ]
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
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}
