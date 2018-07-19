<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use moonland\phpexcel\Excel;
use app\entities\User;
use DateTime;
use DateTimeZone;
use app\entities\Conference;
use app\components\UserComponent;

/**
 * Class ServiceController
 * @package app\commands
 */
class ServiceController extends Controller
{
    /**
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionUploadUsers()
    {
        $users = Excel::import('/var/www/cert.dwbx.ru/storage/participants.xlsx', $config = []);

        if ($users) {
            echo 'Файл считан!' . PHP_EOL;

            $i = 0;

            foreach ($users as $user) {
                $newUser = User::create(
                    trim($user['first_name']),
                    trim($user['last_name']),
                    trim($user['patron_name']),
                    !empty(trim($user['organization'])) ? trim($user['organization']) : null,
                    !empty(trim($user['post'])) ? trim($user['post']) : null,
                    !empty(trim($user['speciality'])) ? trim($user['speciality']) : null,
                    !empty(trim($user['email'])) ? trim($user['email']) : null,
                    12345,
                    !empty(trim($user['code'])) ? trim($user['code']) : null
                );

                if ($newUser->save(false)) {
                    $userRole = Yii::$app->authManager->getRole(User::ROLE_PARTICIPANT);

                    Yii::$app->authManager->assign($userRole, $newUser->id) ? true : false;

                    echo 'Пользователь ' . $newUser->last_name . ' ' . $newUser->first_name . ' ' . $newUser->patron_name . ' создан' . PHP_EOL ;

                    if (!empty($user['dates'])) {
                        $inputDates = explode(';', $user['dates']);

                        $cleanDates = [];

                        //delete empty element
                        foreach ($inputDates as $inputPart) {
                            if (!empty(trim($inputPart))) {
                                $cleanDates[] = trim($inputPart);
                            }
                        }

                        foreach ($cleanDates as $date) {
                            if ($date == '11.11.1111') {
                                $date = '11.11.1991';
                            }

                            $date = $date . ' 12:00';

                            $dateObject = DateTime::createFromFormat('d.m.Y H:i', $date, new DateTimeZone('Europe/Moscow'));

                            $timeStamp = $dateObject->getTimestamp();

                            $conference = Conference::findOne(['start_time' => $timeStamp]);

                            if (!$conference) {
                                $conference = new Conference();

                                $conference->title = 'Клинико-анатомическая конференция (' . $date . ')';
                                $conference->author_id = 1;
                                $conference->description = 'Клинико-анатомическая конференция (' . $date . ')';
                                $conference->start_time = $timeStamp;
                                $conference->end_time = $timeStamp;

                                $conference->save();
                            }

                            $results = UserComponent::registerParticipant($newUser->id, $conference->id, Conference::LEARNING_FULL_TIME);

                            echo $results['message'] . PHP_EOL;
                        }
                    }

                    $i++;
                } else {
                    echo 'Ошибка создания пользователя!';
                }
            }

            echo 'Всего создано ' . $i . ' пользователей' . PHP_EOL;

            exit();
        } else {
            echo 'Ошибка чтения файла!';

            exit();
        }
    }
}
