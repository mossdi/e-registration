<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\entities\User;

class RoleController extends Controller
{
    /**
     * @param $role
     * @param null $description
     * @return int
     * @throws \Exception
     */
    public function actionCreateRole($role, $description = null)
    {
        $role = Yii::$app->authManager->createRole($role);

        $role->description = $description;

        Yii::$app->authManager->add($role);

        echo 'Роль ' . $role->name . ' добавлена!' . PHP_EOL;
        return ExitCode::OK;
    }

    /**
     * @param $role
     * @param $user_phone
     * @return int
     */
    public function actionAssignRole($role, $user_phone)
    {
        $user = User::findByPhone($user_phone);

        if (!$user) {
            echo 'Пользователь c номером ' . $user_phone . ' не найден' . "\n";
            return ExitCode::NOUSER;
        }

        $userRole = Yii::$app->authManager->getRole($role);

        try {
            Yii::$app->authManager->assign($userRole, $user->id);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        echo 'Пользователю ' . $user->first_name . ' ' . $user->last_name . ' присвоена роль ' . $role . '!' . "\n";
        return ExitCode::OK;
    }
}
