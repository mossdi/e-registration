<?php

namespace app\components;

use app\entities\Certificate;
use app\forms\CertificateForm;

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
}
