<?php

namespace app\entities\mongodb;

use yii\mongodb\ActiveRecord;

/**
 * Class Test
 * @package app\entities\mongodb
 */
class Test extends ActiveRecord
{
    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return 'test';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'date',
        ];
    }
}
