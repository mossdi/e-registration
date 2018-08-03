<?php

namespace app\components;

use Yii;

/**
 * Class SendMailComponent
 * @package app\components
 */
class SendMailComponent
{
    public static function sendMail($mailTo, $htmlBody)
    {
        $mailer = Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['smtpParams']['username'] => 'ГБУ НИИОЗММ ДЗМ'])
            ->setTo($mailTo)
            ->setSubject('Данные для авторизации в системе Сертифицирования')
            ->setHtmlBody($htmlBody);

        $mailer->send();
    }
}
