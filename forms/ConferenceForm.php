<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\entities\Conference;

class ConferenceForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $start_time;

    /**
     * ConferenceForm constructor.
     * @param null $id
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($id = null)
    {
        if ($id != null) {
            $conference = Conference::findOne($id);

            $this->id = $conference->id;
            $this->title = $conference->title;
            $this->description = $conference->description;
            $this->start_time = Yii::$app->formatter->asDate($conference->start_time,'php:d.m.y H:i');
        }
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
