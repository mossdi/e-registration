<?php

namespace app\components;

use Yii;
use app\entities\Certificate;
use app\forms\CertificateForm;
use mikehaertl\wkhtmlto\Pdf;

/**
 * Class CertificateComponent
 * @package app\components
 */
class CertificateComponent
{
    /**
     * @param CertificateForm $form
     * @param Certificate $certificate
     * @return Certificate
     */
    public static function certificateUpdate(CertificateForm $form, Certificate $certificate)
    {
        $certificate->date_issue = $form->date_issue;
        $certificate->document_series = $form->document_series;
        $certificate->status = $form->status;

        if (!$certificate->save()) {
            throw new \RuntimeException('Ошибка обновления сертификата');
        }

        return $certificate;
    }

    /**
     * Certificate download
     * @param $id
     * @return mixed
     */
    public static function certificateDownload($id) {
        $certificate = Certificate::findOne($id);

        $template = Yii::$app->controller->renderPartial('/html_block/element/certificate', [
            'certificate' => $certificate,
        ]);

        $image = new Pdf();

        $image->addPage($template);

        $image->setOptions([
            'orientation'   => 'Landscape',
            'margin-left'   => '0mm',
            'margin-right'  => '0mm',
            'margin-top'    => '0mm',
            'margin-bottom' => '0mm'
        ]);

        return $image->send();
    }
}
