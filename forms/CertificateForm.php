<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\entities\Certificate;

class CertificateForm extends Model
{
    public $id;
    public $date_issue;
    public $document_series;
    public $status;

    /**
     * Certificate form constructor.
     * @param null $id
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($id = null)
    {
        if ($id != null) {
            $certificate = Certificate::findOne($id);

            $this->id = $certificate->id;
            $this->date_issue = Yii::$app->formatter->asDate($certificate->date_issue,'php:d.m.yy');
            $this->document_series = $certificate->document_series;
            $this->status = $certificate->status;
        }

        return parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_issue', 'document_series'], 'required'],
            [['date_issue'], 'date', 'format' => 'php:d.m.yy'],
            [['document_series'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'date_issue' => 'Дата выдачи',
            'document_series' => 'Номер документа',
            'status' => 'Статус',
        ];
    }
}
