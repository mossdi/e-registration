<?php

namespace app\services\certificate;

use Yii;
use app\entities\Certificate;
use app\forms\CertificateForm;
use mikehaertl\wkhtmlto\Pdf;
use DateTime;
use DateTimeZone;

/**
 * Class CertificateService
 * @package app\services\certificate
 */
class CertificateService
{
    /**
     * @param CertificateForm $form
     * @param Certificate $certificate
     * @return Certificate
     */
    public static function certificateUpdate(CertificateForm $form, Certificate $certificate) : Certificate
    {
        $date = DateTime::createFromFormat('d.m.yy', $form->date_issue, new DateTimeZone('Europe/Moscow'));

        $certificate->date_issue = $date->getTimestamp();
        $certificate->document_series = $form->document_series;
        $certificate->verification_code = $form->verification_code;
        $certificate->status = $form->status;

        if (!$certificate->save()) {
            throw new \RuntimeException('Ошибка обновления сертификата');
        }

        return $certificate;
    }

    /**
     * Certificate download
     * @param $id
     * @return bool
     */
    public static function certificateDownload(int $id) : bool
    {
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
