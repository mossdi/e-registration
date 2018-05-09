<?php

namespace app\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use DateTime;
use DateTimeZone;

/**
 * Class Conference
 *
 * @package app\entities
 * @property int $id [INT(10)]
 * @property string $title [VARCHAR(255)]
 * @property int $author_id [INT(10)]
 * @property string $description [TEXT(65535)]
 * @property int $start_time [INT(10)]
 * @property int $end_time [INT(10)]
 * @property int $status [SMALLINT(5)]
 * @property int $deleted [SMALLINT(5)]
 * @property int $created_at [INT(10)]
 * @property int $updated_at [INT(10)]
 * @property UserToConference $studentCount
 */
class Conference extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conference';
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
        $date = DateTime::createFromFormat('d.m.y H:i', $this->start_time, new DateTimeZone('Europe/Moscow'));

        $this->start_time = $date->getTimestamp();

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'author_id' => 'Ведущий',
            'description' => 'Описание',
            'start_time' => 'Дата/время начала конференции',
        ];
    }

    /**
     * @param $title
     * @param $description
     * @param $start_time
     * @return Conference
     */
    public static function create($title, $description, $start_time)
    {
        $conference = new self();

        $conference->author_id = Yii::$app->user->id;
        $conference->title = $title;
        $conference->description = $description;
        $conference->start_time = $start_time;
        $conference->status = self::STATUS_ACTIVE;
        $conference->created_at = time();

        return $conference;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorInfo()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return int|string
     */
    public function getStudentCount()
    {
        return $this->hasMany(UserToConference::className(), ['conference_id' => 'id'])
            ->count();
    }
}
