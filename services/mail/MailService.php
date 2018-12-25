<?php

namespace app\services\mail;

use Yii;

/**
 * Class MailService
 * @package app\services\mail
 */
class MailService
{
    public static function sendMail($mailTo, $htmlBody) : bool
    {
        $mailer = Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['smtpParams']['username'] => 'ГБУ НИИОЗММ ДЗМ'])
            ->setTo($mailTo)
            ->setSubject('Данные для авторизации в системе Сертифицирования')
            ->setHtmlBody($htmlBody);

        $mailer->send();
    }
}
