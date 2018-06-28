<?php

namespace app\entities;

use yii\db\ActiveRecord;

/**
 * Class Question
 * @property int $id
 * @property string $question
 * @property int $conference_id
 * @property int $sort_order
 */
class Question extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
          [['question', 'sort_order'], 'required']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'question' => 'Вопрос',
            'sort_order' => 'Порядок сортировки'
        ];
    }
}
