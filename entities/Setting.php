<?php

namespace app\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $label
 * @property string $type
 */
class Setting extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['value', 'required'],
            ['value', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'param' => 'Параметр',
            'value' => 'Значение',
            'label' => 'Название',
            'type' => 'Тип',
        ];
    }
}
