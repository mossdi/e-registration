<?php

namespace app\services\conference;

use Yii;
use app\forms\ConferenceForm;
use app\entities\Conference;

/**
 * Class ConferenceService
 * @package app\services\conference
 */
class ConferenceService
{
    /**
     * @param ConferenceForm $form
     * @return Conference
     */
    public static function conferenceCreate(ConferenceForm $form) : Conference
    {
        $conference = Conference::create(
            $form->title,
            $form->description,
            $form->start_time
        );

        if (!$conference->save()) {
            throw new \RuntimeException('Ошибка создания конференции');
        }

        return $conference;
    }

    /**
     * @param ConferenceForm $form
     * @param Conference $conference
     * @return Conference
     */
    public static function conferenceUpdate(ConferenceForm $form, Conference $conference) : Conference
    {
        $conference->title = $form->title;
        $conference->description = $form->description;
        $conference->start_time = $form->start_time;

        if (!$conference->save()) {
            throw new \RuntimeException('Ошибка обновления конференции');
        }

        return $conference;
    }

    /**
     * @return Conference|null
     */
    public static function conferenceCurrent()
    {
        $conference_current = Conference::find()
               ->where(['<=', '(start_time - ' . Yii::$app->setting->get('registerOpen') .')', time()])
            ->andWhere(['is', 'end_time', null])
            ->andWhere(['status' => Conference::STATUS_ACTIVE,])
            ->andWhere(['deleted' => 0])
             ->orderBy(['start_time' => SORT_ASC])
                 ->one();

        return $conference_current;
    }
}
