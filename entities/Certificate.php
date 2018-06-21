<?php

namespace app\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use DateTime;
use DateTimeZone;

/**
 * Class Certificate
 * @package app\entities
 * @property int $id
 * @property int $user_id
 * @property int $conference_id
 * @property int $date_issue
 * @property string $document_series
 * @property string $learning_method
 * @property int $status
 * @property int $deleted
 * @property int $created_at
 * @property int $updated_at
 * @property User $user
 * @property Conference $conference
 */
class Certificate extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static $statusList = [
        self::STATUS_ACTIVE  => 'Активный',
        self::STATUS_DELETED => 'Заблокированный',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'certificate';
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
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!empty($this->date_issue)) {
            $date = DateTime::createFromFormat('d.m.y', $this->date_issue, new DateTimeZone('Europe/Moscow'));

            $this->date_issue = $date->getTimestamp();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['date_issue', 'document_series'], 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['deleted', 'default', 'value' => 0],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @param $user_id
     * @param $conference_id
     * @param $learning_method
     * @return Certificate
     */
    public static function create($user_id, $conference_id, $learning_method)
    {
        $certificate = new self();

        $certificate->user_id = $user_id;
        $certificate->conference_id = $conference_id;
        $certificate->learning_method = $learning_method;

        return $certificate;
    }

    public function attributeLabels()
    {
        return [
            'userLastName' => 'Фамилия',
            'userFirstName' => 'Имя',
            'userPatronName' => 'Отчество',
            'userFullName' => 'Владелец',
            'participantMethod' => 'Форма обучения',
            'conference.title' => 'Конференция',
            'date_issue' => 'Дата выдачи',
            'document_series' => 'Номер документа',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getUserFullName() {
        return $this->user->last_name . ' ' . $this->user->first_name . ' ' . $this->user->patron_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public  function getConference()
    {
        return$this->hasOne(Conference::className(), ['id' => 'conference_id']);
    }

    /**
     * @return string
     */
    public function getParticipantMethod()
    {
        $model = ConferenceParticipant::findOne(['conference_id' => $this->conference_id, 'user_id' => Yii::$app->user->id]);

        return !empty($model) && $model->method == Conference::LEARNING_FULL_TIME ? 'Очно' : 'Дистанционно';
    }
}
