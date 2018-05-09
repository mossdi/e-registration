<?php

namespace app\components;

use app\forms\ConferenceForm;
use app\entities\Conference;

class ConferenceComponent
{
    /**
     * @param ConferenceForm $form
     * @return Conference
     */
    public function conferenceCreate(ConferenceForm $form)
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
    public function conferenceUpdate(ConferenceForm $form, Conference $conference)
    {
        $conference->title = $form->title;
        $conference->description = $form->description;
        $conference->start_time = $form->start_time;

        if (!$conference->save()) {
            throw new \RuntimeException('Ошибка обновления конференции');
        }

        return $conference;
    }
}
