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
 * @property int $id [INT(10)]
 * @property string $first_name [VARCHAR(255)]
 * @property string $last_name [VARCHAR(255)]
 * @property string $patron_name [VARCHAR(255)]
 * @property string $organization [VARCHAR(255)]
 * @property string $post [VARCHAR(255)]
 * @property string $passport [INT(10)]
 * @property string $email [VARCHAR(255)]
 * @property string $phone [INT(10)]
 * @property string $auth_key [VARCHAR(32)]
 * @property string $password_hash [VARCHAR(255)]
 * @property string $password_reset_token [VARCHAR(255)]
 * @property int $status [SMALLINT(5)]
 * @property int $deleted [SMALLINT(5)]
 * @property int $created_at [INT(10)]
 * @property int $updated_at [INT(10)]
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static $statusList = [
        self::STATUS_ACTIVE  => 'Включено',
        self::STATUS_DELETED => 'Заблокирован',
    ];

    public static $roleList = [
        'student' => 'Слушатель',
        'speaker' => 'Ведущий',
        'receptionist' => 'Регистратор',
        'admin' => 'Администратор',
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
     * @param $passport
     * @param $phone
     * @param $email
     * @param $password
     * @return User
     * @throws Exception
     */
    public static function create($first_name, $last_name, $patron_name, $organization, $post, $passport, $phone, $email, $password)
    {
        $user = new self();

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->patron_name = $patron_name;
        $user->organization = $organization;
        $user->post = $post;
        $user->passport = $passport;
        $user->email = mb_strtolower($email);
        $user->phone = $phone;
        $user->status = self::STATUS_ACTIVE;
        $user->created_at = time();

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
     * Finds user by phone
     *
     * @param $phone
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return self::findOne(['phone' => $phone, 'status' => self::STATUS_ACTIVE]);
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
