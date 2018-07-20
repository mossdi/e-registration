<?php

namespace app\entities;

use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property int $code
 * @property string $first_name
 * @property string $last_name
 * @property string $patron_name
 * @property string $organization
 * @property string $post
 * @property string $speciality
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property int $status
 * @property int $deleted
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static $statusList = [
        self::STATUS_ACTIVE  => 'Включено',
        self::STATUS_DELETED => 'Заблокирован',
    ];

    const ROLE_PARTICIPANT  = 'participant';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_RECEPTIONIST_CURATOR = 'receptionist-curator';
    const ROLE_SPEAKER      = 'speaker';
    const ROLE_ADMIN        = 'admin';

    public static $roleList = [
        self::ROLE_PARTICIPANT          => 'Слушатель',
        self::ROLE_SPEAKER              => 'Ведущий',
        self::ROLE_RECEPTIONIST         => 'Регистратор',
        self::ROLE_RECEPTIONIST_CURATOR => 'Регистратор-куратор',
        self::ROLE_ADMIN                => 'Администратор',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
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
            'post' => 'Должность',
            'email' => 'Email',
            'icon' => 'Аватар',
            'status' => 'Статус'
        ];
    }

    /**
     * @param $first_name
     * @param $last_name
     * @param $patron_name
     * @param $organization
     * @param $post
     * @param $speciality
     * @param $email
     * @param $password
     * @param $code null
     * @return User
     * @throws Exception
     */
    public static function create($first_name, $last_name, $patron_name, $organization, $post, $speciality, $email, $password, $code = null)
    {
        $user = new self();

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->patron_name = $patron_name;
        $user->organization = $organization;
        $user->post = $post;
        $user->speciality = $speciality;
        $user->email = mb_strtolower($email);
        $user->status = self::STATUS_ACTIVE;
        $user->created_at = time();
        $user->code = $code;

        $user->generateAuthKey();
        $user->setPassword($password);

        return $user;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
