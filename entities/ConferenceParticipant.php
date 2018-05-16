<?php

namespace app\entities;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "conference_participant".
 *
 * @property int $user_id
 * @property int $conference_id
 * @property string $method
 * @property int $created_at
 * @property int $updated_at
 * @property Conference $conference
 * @property User $user
 */
class ConferenceParticipant extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conference_participant';
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
     * @return \yii\db\ActiveQuery
     */
    public function getConference()
    {
        return $this->hasOne(Conference::className(), ['id' => 'conference_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return$this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
