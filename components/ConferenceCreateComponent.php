<?php
namespace app\components;

use app\forms\ConferenceForm;
use app\entities\Conference;

class ConferenceCreateComponent
{
    /**
     * @param ConferenceForm $form
     * @return Conference
     */
    public function create(ConferenceForm $form)
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
}
