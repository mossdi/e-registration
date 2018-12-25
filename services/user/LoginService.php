<?php

namespace app\services\user;

use Yii;
use app\entities\User;
use app\forms\LoginForm;

/**
 * Class LoginService
 * @package app\services\user
 */
class LoginService
{
    /**
     * @param LoginForm $form
     * @return bool
     */
    public static function login(LoginForm $form) : bool
    {
        return Yii::$app->user->login(
            User::findByEmail($form->email),
            $form->rememberMe ? 3600 * 24 * 30 : 0
        );
    }
}
