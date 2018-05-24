<?php

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "conference_wishlist".
 *
 * @property int $user_id
 * @property int $conference_id
 * @property Conference $conference
 */
class ConferenceWishlist extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conference_wishlist';
    }

    public function attributeLabels()
    {
        return [
            'conferenceStart' => 'Дата/время начала конференции'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConference()
    {
        return $this->hasOne(Conference::className(), ['id' => 'conference_id'])
                ->with(['author'])
               ->where(['conference.status' => Conference::STATUS_ACTIVE])
            ->andWhere(['conference.deleted' => 0])
             ->orderBy(['conference.start_time' => SORT_ASC]);
    }
}
