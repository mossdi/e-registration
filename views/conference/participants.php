<?php

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $conference \app\entities\Conference */

use yii\grid\GridView;
use yii\helpers\Html;
use yii2mod\alert\Alert;
use yii\widgets\Pjax;
use app\entities\User;
use app\entities\Conference;

Pjax::begin([
    'id' => 'participantsListContainer',
    'enablePushState' => false,
]);

try {
    echo Alert::widget();
} catch (Exception $e) {
    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
}

echo Html::button('Обновить список участников <span style="margin-left: 5px;" class="glyphicon glyphicon-refresh"></span>', [
    'class' => 'btn btn-default col-margin-bottom-10',
    'onclick' => 'formLoad(\'/conference/participant\', \'modal\', \'' . $conference->title . '\', \'' . $conference->id . '\'); participantCountReload();'
]);

try {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'label' => 'Фамилия',
                'attribute' => 'userLastName',
                'value' => function($model) {
                    return $model->user->last_name;
                }
            ],
            [
                'label' => 'Имя',
                'attribute' => 'userFirstName',
                'value' => function($model) {
                    return $model->user->first_name;
                }
            ],
            [
                'label' => 'Отчество',
                'attribute' => 'userPatronName',
                'value' => function($model) {
                    return $model->user->patron_name;
                }
            ],
            [
                'label' => 'Организация',
                'attribute' => 'userOrganization',
                'value' => function($model) {
                    return $model->user->organization;
                }
            ],
            [
                'label' => 'Должность',
                'attribute' => 'userPost',
                'value' => function($model) {
                    return $model->user->post;
                }
            ],

            [
                'label' => 'Присутствие',
                'attribute' => 'method',
                'value' => function($model) {
                    return $model->method == Conference::LEARNING_FULL_TIME ? 'Очно' : 'Дистанционно';
                },
                'enableSorting' => false,
                'filter' => Html::activeDropDownList($searchModel, 'method', [
                    Conference::LEARNING_FULL_TIME => 'Очно',
                    Conference::LEARNING_DISTANCE  => 'Дистанционно',
                ], ['prompt' => 'Все', 'class' => 'form-control'])
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)) && $conference->end_time == null ? '{delete}' : '<i class="fa fa-check-circle"></i>',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/conference/delete-participant?user_id=' . $model->user_id . '&conference_id=' . $model->conference_id], [
                            'class'   => 'btn',
                            'data-pjax' => true,
                            'data-confirm' => 'Вы уверены, что хотите удалить пользователя с конференции?',
                        ]);
                    },
                ]
            ],
        ],
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}

Pjax::end();
