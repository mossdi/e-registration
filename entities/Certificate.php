<?php

namespace app\entities;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Certificate
 * @package app\entities
 * @property int $id
 * @property int $author_id
 * @property int $owner_id
 * @property int $conference_id
 * @property int $date_issue
 * @property int $document_series
 * @property int $learning_method
 * @property int $status
 * @property int $deleted
 * @property int $created_at
 * @property int $updated_at
 */
class Certificate extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
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

    public function create()
    {

    }
}
