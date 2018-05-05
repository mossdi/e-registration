<?php

namespace app\components;

use Yii;

class SendMailComponent
{
    public function sendMail($mailTo, $htmlBody)
    {
        Yii::$app->mailer->compose()
            ->setFrom('admin@localhost')
            ->setTo($mailTo)
            ->setSubject('Данные для авторизации в системе')
            ->setHtmlBody($htmlBody)
            ->send();
    }
}
