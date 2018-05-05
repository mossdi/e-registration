<?php

namespace app\forms;

use yii\base\Model;
use app\entities\User;

/**
 * User form
 */
class UserForm extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CONFERENCE = 'conference';

    public $id;
    public $first_name;
    public $last_name;
    public $patron_name;
    public $organization;
    public $post;
    public $passport;
    public $phone;
    public $email;
    public $password;
    public $role;

    /**
     * UserForm constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        if ($id != null) {
            $user = User::findOne($id);

            $this->id = $user->id;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->patron_name = $user->patron_name;
            $this->organization = $user->organization;
            $this->post = $user->post;
            $this->passport = $user->passport;
            $this->phone = $user->phone;
            $this->email = $user->email;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone', 'unique', 'targetClass' => 'app\entities\User', 'message' => 'Такой телефон уже зарегистрирован', 'on' => self::SCENARIO_CREATE],
            ['email', 'unique', 'targetClass' => 'app\entities\User', 'message' => 'Такая эл.почта уже зарегистрирована', 'on' => self::SCENARIO_CREATE],
            ['passport', 'unique', 'targetClass' => 'app\entities\User', 'message' => 'Такой паспорт уже зарегистрирован', 'on' => self::SCENARIO_CREATE],

            [['first_name', 'last_name', 'patron_name', 'organization', 'post', 'passport', 'phone', 'email'], 'required'],
            [['first_name', 'last_name', 'patron_name', 'organization', 'post', 'password', 'role'], 'string'],
            ['phone', 'match', 'pattern' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'],
            ['passport', 'integer'],
            ['email', 'email'],

            [['first_name', 'last_name', 'patron_name', 'email'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patron_name' => 'Отчество',
            'organization' => 'Организация',
            'post' => 'Должность',
            'passport' => 'Паспорт',
            'phone' => 'Телефон',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Роль',
        ];
    }
}
