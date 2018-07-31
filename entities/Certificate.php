<?php

namespace app\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Certificate
 * @package app\entities
 * @property int $id
 * @property int $user_id
 * @property int $conference_id
 * @property int $date_issue
 * @property string $document_series
 * @property string $verification_code
 * @property int $status
 * @property int $deleted
 * @property int $created_at
 * @property int $updated_at
 * @property User $user
 * @property Conference $conference
 */
class Certificate extends ActiveRecord
{
    const DOWNLOAD_FOLDER = '/var/www/cert.dwbx.ru/storage/certificate/';

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
     * @param $date_issue
     * @return Certificate
     */
    public static function create($user_id, $conference_id, $date_issue)
    {
        $certificate = new self();

        $certificate->user_id = $user_id;
        $certificate->conference_id = $conference_id;
        $certificate->date_issue = $date_issue;

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
            'verification_code' => 'Код подтверждения',
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
        return $this->hasOne(Conference::className(), ['id' => 'conference_id']);
    }

    /**
     * @return string
     */
    public function getParticipantMethod()
    {
        $model = ConferenceParticipant::findOne([
            'conference_id' => $this->conference_id,
            'user_id' => $this->user_id
        ]);

        if (!empty($model)) {
            return $model->method == Conference::LEARNING_FULL_TIME ? Conference::LEARNING_FULL_TIME : Conference::LEARNING_DISTANCE;
        }
    }
}
