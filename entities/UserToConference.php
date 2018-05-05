<?php

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_to_conference".
 *
 * @property int $user_id
 * @property int $conference_id
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

    public function getConference()
    {
        return $this->hasOne(Conference::className(), ['id' => 'conference_id']);
    }
}

