<?php

namespace app\components;

use Yii;
use app\forms\UserForm;
use app\entities\User;
use app\entities\Conference;
use app\entities\UserToConference;

class UserComponent
{
    /**
     * @param UserForm $form
     * @return User
     * @throws \Exception
     */
    public function userSignup(UserForm $form)
    {
        $password = Yii::$app->security->generateRandomString(8);

        $user = User::create(
            $form->first_name,
            $form->last_name,
            $form->patron_name,
            $form->organization,
            $form->post,
            $form->passport,
            $form->phone,
            $form->email,
            $password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Ошибка создания пользователя');
        }

        $this->assignRole(!empty($form->role) ? $form->role : 'student', $form->phone);

        (new SendMailComponent())->sendMail($form->email,'<p>Логин: ' . $form->phone . '</p><p>Пароль: ' . $password . '</p>');

        return $user;
    }

    /**
     * @param UserForm $form
     * @param User $user
     * @throws \Exception
     * @return User
     */
    public function userUpdate(UserForm $form, User $user)
    {
        $user->first_name = $form->first_name;
        $user->last_name = $form->last_name;
        $user->patron_name = $form->patron_name;
        $user->organization = $form->organization;
        $user->post = $form->post;
        $user->passport = $form->passport;
        $user->email = mb_strtolower($form->email);
        $user->phone = $form->phone;
        $user->status = User::STATUS_ACTIVE;
        $user->updated_at = time();

        $user->generateAuthKey();

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }

        if (!$user->save()) {
            throw new \RuntimeException('Ошибка обновления пользователя');
        }

        return $user;
    }

    /**
     * @param UserForm $form
     * @throws \Exception
     * @return array|string
     */
    public function singupToConference(UserForm $form)
    {
        $conference = Conference::find()
            ->where(['status' => 10])
            ->limit(1)
            ->orderBy(['start_time' => SORT_ASC])
            ->one();

        if (!$conference) {
            return [
                'status'  => 'error',
                'message' => 'Нет ни одной запланированной конференции!'
            ];
        }

        $user = User::findOne(['phone' => $form->phone]);

        if (!empty($user) && UserToConference::findOne(['user_id' => $user->id, 'conference_id' => $conference->id])) {
            return [
                'status'  => 'success',
                'message' => 'Этот пользователь уже зарегистрирован на конференцию - ' . $conference->title
            ];
        } elseif (empty($user)) {
            $user = $this->userSignup($form);
        }

        $register_member = new UserToConference();
        
        $register_member->user_id = $user->id;
        $register_member->conference_id = $conference->id;

        return $register_member->save() ? [
            'status'  => 'success',
            'message' => 'Пользователь успешно зарегистрирован на конференцию - ' . $conference->title
        ] : [
            'status'  => 'error',
            'message' => 'Ошибка. Пользователь не зарегистрирован на конференцию. Обратитесь к администратору системы.'
        ];
    }

    /**
     * @param $role
     * @param $user_phone
     * @throws \Exception
     * @return mixed
     */
    public function assignRole($role, $user_phone)
    {
        $user = User::findByPhone($user_phone);

        $userRole = Yii::$app->authManager->getRole($role);

        return Yii::$app->authManager->assign($userRole, $user->id) ? true : false;
    }
}
