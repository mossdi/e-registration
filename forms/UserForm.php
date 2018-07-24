<?php

namespace app\forms;

use yii\base\Model;
use app\entities\User;

/**
 * User form
 */
class UserForm extends Model
{
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_PARTICIPANT_UPDATE = 'participant-update';
    const SCENARIO_CREATE_PAGE = 'create-page';
    const SCENARIO_REGISTER_PARTICIPANT_PAGE = 'register-participant-page';

    const LOAD_FORM_TO_PAGE = 'page';
    const LOAD_FORM_TO_MODAL = 'modal';

    public $id;
    public $first_name;
    public $last_name;
    public $patron_name;
    public $organization;
    public $organization_branch;
    public $post;
    public $speciality;
    public $email;
    public $password;
    public $role;
    public $conference;
    public $without_patron_name;

    /**
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
            $this->organization_branch = $user->organization_branch;
            $this->post = $user->post;
            $this->speciality = $user->speciality;
            $this->email = $user->email;
        }

        return parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required', 'on' => [self::SCENARIO_CREATE_PAGE, self::SCENARIO_UPDATE, self::SCENARIO_PARTICIPANT_UPDATE, self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_PARTICIPANT_PAGE]],
            ['conference', 'required', 'on' => self::SCENARIO_REGISTER_PARTICIPANT_PAGE],
            ['password', 'required', 'on' => self::SCENARIO_REGISTER],

            [['first_name', 'last_name', 'patron_name', 'organization', 'organization_branch', 'post', 'speciality', 'password', 'role'], 'string'],
            [['id', 'conference'], 'integer'],
            ['email', 'email', 'on' => [self::SCENARIO_REGISTER]],

            ['email', 'unique', 'targetClass' => 'app\entities\User', 'message' => 'Такая эл.почта уже зарегистрирована', 'on' => [self::SCENARIO_CREATE_PAGE, self::SCENARIO_REGISTER]],

            ['role', 'default', 'value' => User::ROLE_PARTICIPANT],

            [['first_name', 'last_name', 'patron_name', 'email'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patron_name' => 'Отчество',
            'organization' => 'Организация',
            'organization_branch' => 'Филиал',
            'post' => 'Должность',
            'speciality' => 'Специализация',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Роль',
            'conference' => 'Зарегистрировать на конференцию',
            'without_patron_name' => 'Без отчества',
        ];
    }
}
