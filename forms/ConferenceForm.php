<?php

namespace app\forms;

use yii\base\Model;

class ConferenceForm extends Model
{
    public $title;
    public $description;
    public $start_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'start_time'], 'required'],
            [['start_time'], 'date', 'format' => 'php:d.m.y H:i'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Наименование',
            'description' => 'Описание',
            'start_time' => 'Дата/время начала',
        ];
    }
}
