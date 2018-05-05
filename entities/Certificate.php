<?php

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * Class Certificate
 * @package app\entities
 * @property int $id [INT(10)]
 * @property int $author_id [INT(10)]
 * @property int $owner_id [INT(10)]
 * @property int $conference_id [INT(10)]
 * @property int $date_issue [INT(10)]
 * @property int $document_series [INT(10)]
 * @property int $status [SMALLINT(5)]
 * @property int $created_at [INT(10)]
 * @property int $updated_at [INT(10)]
 */
class Certificate extends ActiveRecord
{
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
