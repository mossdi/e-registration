<?php

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_to_conference".
 *
 * @property int $user_id
 * @property int $conference_id
 * @property Conference $conference
 * @property User $user
 */
class UserToConference extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_to_conference';
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

