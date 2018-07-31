<?php

namespace app\commands;

use app\entities\Certificate;
use Yii;
use DateTime;
use DateTimeZone;
use yii\console\Controller;
use moonland\phpexcel\Excel;
use app\entities\User;
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

            foreach ($users[0] as $user) {
                $newUser = User::create(
                    trim($user['first_name']),
                    trim($user['last_name']),
                    trim($user['patron_name']),
                    !empty(trim($user['organization'])) ? trim($user['organization']) : null,
                    !empty(trim($user['organization_branch'])) ? trim($user['organization_branch']) : null,
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

    /**
     * @param $attribute
     */
    public function actionUpdateUsers($attribute)
    {
        $users = Excel::import('/var/www/cert.dwbx.ru/storage/participants.xlsx', $config = []);

        if ($users) {
            echo 'Файл считан!' . PHP_EOL;

            $i = 0;

            foreach ($users as $user) {
                $userUpdate = User::findOne(['code' => $user['code']]); //Поиск по столшбцу код

                $userUpdate->updateAttributes([$attribute => $user[$attribute]]);

                echo $userUpdate->last_name . ' ' . $userUpdate->first_name . ' ' . $userUpdate->patron_name . ' - обновлен!' . PHP_EOL;
                echo $user[$attribute] . PHP_EOL;

                $i++;
            }

            echo 'Всего обновлено записей - ' . $i . PHP_EOL;
        } else {
            echo 'Ошибка чтения файла!';

            exit();
        }
    }

    /**
     * @param $conference_id
     */
    public function actionCertificateIssue($conference_id)
    {
        $certificates = Excel::import('/var/www/cert.dwbx.ru/storage/certificates.xlsx', $config = []);

        $i = 0;

        foreach ($certificates as $certificate) {
            $fullName = explode(' ', $certificate['full_name']);

            $cleanName = [];

            //delete empty element
            foreach ($fullName as $namePart) {
                if (!empty(trim($namePart))) {
                    $cleanName[] = trim($namePart);
                }
            }

            $user = User::find();

            if (!empty($cleanName[0])) {
                $user->andWhere('last_name LIKE \'' . $cleanName[0] . '%\'');
            }
            if (!empty($cleanName[1])) {
                $user->andWhere('first_name LIKE \'' . $cleanName[1] . '%\'');
            }
            if (!empty($cleanName[2])) {
                $user->andWhere('patron_name LIKE \'' . $cleanName[2] . '%\'');
            }

            $userFind = $user->one();

            if ($userFind) {
                $userCertificate = Certificate::findOne([
                    'user_id' => $userFind->id,
                    'conference_id' => $conference_id
                ]);

                $userCertificate->updateAttributes([
                    'document_series' => $certificate['document_series'],
                    'verification_code' => $certificate['verification_code'],
                ]);

                $conference = Conference::findOne($conference_id);

                if ($userCertificate->date_issue == null) {
                    $userCertificate->updateAttributes([
                        'date_issue' => $conference->start_time
                    ]);
                }

                echo 'Выдан сертификат на имя ' . $userFind->last_name . ' ' . $userFind->first_name . ' ' . $userFind->patron_name . ' (' . $conference->title . ')' . PHP_EOL;

                $i++;
            } else {
                echo 'Не найден пользователь ' . $cleanName[0] . ' ' . $cleanName[1] . ' ' . $cleanName[2] . PHP_EOL;
            }
        }

        echo 'Всего выдано ' .$i . ' сертификатов' . PHP_EOL;
    }
}
