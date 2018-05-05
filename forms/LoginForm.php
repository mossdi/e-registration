<?php

namespace app\forms;

use yii\base\Model;
use app\components\validators\PasswordValidator;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $password;
    public $rememberMe = true;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone', 'required', 'message' => 'Введите телефон'],
            ['password', 'required', 'message' => 'Введите пароль'],
            ['rememberMe', 'boolean'],
            ['phone', 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Неверный формат телефона'],
            ['password', PasswordValidator::className()],
        ];
    }
}
