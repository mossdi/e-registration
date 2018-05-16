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
     * @return int
     * @throws \Exception
     */
    public function actionCreateAllRole()
    {
        foreach (User::$roleList as $role => $description) {

            $role = Yii::$app->authManager->createRole($role);

            $role->description = $description;

            Yii::$app->authManager->add($role);

            echo 'Роль ' . $role->name . ' добавлена!' . PHP_EOL;
        }
    }

    /**
     * @param $role
     * @param $id
     * @return int
     */
    public function actionAssignRole($role, $id)
    {
        $user = User::findOne($id);

        if (!$user) {
            echo 'Пользователь c id = ' . $id . ' не найден' . "\n";
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
