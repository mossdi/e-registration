<?php

namespace app\components;

use Yii;
use app\entities\User;
use app\forms\LoginForm;

class LoginComponent
{
    /**
     * @param LoginForm $form
     * @return bool
     */
    public static function login(LoginForm $form)
    {
        return Yii::$app->user->login(
            User::findByPhone($form->phone),
            $form->rememberMe ? 3600 * 24 * 30 : 0
        );
    }
}
