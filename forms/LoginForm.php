<?php

namespace app\forms;

use yii\base\Model;
use app\components\validators\PasswordValidator;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Введите E-mail'],
            ['password', 'required', 'message' => 'Введите пароль'],
            ['rememberMe', 'boolean'],
            ['password', PasswordValidator::className()],
        ];
    }
}
