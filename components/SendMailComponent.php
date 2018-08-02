<?php

namespace app\components;

use Yii;

class SendMailComponent
{
    public static function sendMail($mailTo, $htmlBody)
    {
        $mailer = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpParams']['username'])
            ->setTo($mailTo)
            ->setSubject('Данные для авторизации в системе')
            ->setHtmlBody($htmlBody);

        $mailer->send();
    }
}
