<?php

namespace app\components;

use Yii;
use app\entities\Certificate;
use app\forms\CertificateForm;

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
        ini_set('pcre.backtrack_limit', '5000000');

        $certificate = Certificate::findOne($id);

        $template = Yii::$app->controller->renderAjax('/html_block/element/certificate', [
            'certificate' => $certificate,
        ]);

        $pdf = Yii::$app->pdfRender;
        $pdf->content = $template;

        return $pdf->render();
    }
}
