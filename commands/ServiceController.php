<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use moonland\phpexcel\Excel;
use app\entities\User;

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
                    trim($user['organization']),
                    trim($user['post']),
                    null,
                    !empty(trim($user['email'])) ? trim($user['email']) : null,
                    12345
                );

                if ($newUser->save(false)) {
                    $userRole = Yii::$app->authManager->getRole(User::ROLE_PARTICIPANT);

                    Yii::$app->authManager->assign($userRole, $newUser->id) ? true : false;

                    echo 'Пользователь ' . $newUser->last_name . ' ' . $newUser->first_name . ' ' . $newUser->patron_name . ' создан' . PHP_EOL ;

                    $i++;
                } else {
                    echo 'Ошибка создания!';
                    break;
                }
            }

            echo 'Всего создано ' . $i . ' пользователей';
        }
    }
}
