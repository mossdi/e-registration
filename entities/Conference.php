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
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property string $description
 * @property int $start_time
 * @property int $end_time
 * @property int $status
 * @property int $deleted
 * @property int $created_at
 * @property int $updated_at
 * @property ConferenceParticipant $studentCount
 */
class Conference extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const LEARNING_DISTANCE = 'distance';
    const LEARNING_FULL_TIME = 'full-time';

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return int
     */
    public function getStudentCount()
    {
        return $this->hasMany(ConferenceParticipant::className(), ['conference_id' => 'id'])
            ->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishList()
    {
        return $this->hasOne(ConferenceWishlist::className(), ['conference_id' => 'id'])
            ->where(['conference_wishlist.user_id' => Yii::$app->user->id]);
    }
}
