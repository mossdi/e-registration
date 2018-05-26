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
            [['param', 'value', 'default', 'label', 'type'], 'required'],
            [['value', 'default'], 'string'],
            [['param', 'label', 'type'], 'string', 'max' => 255],
            [['param'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'param' => 'Param',
            'value' => 'Value',
            'default' => 'Default',
            'label' => 'Label',
            'type' => 'Type',
        ];
    }
}
