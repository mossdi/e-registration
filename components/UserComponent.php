<?php

namespace app\components;

use Yii;
use app\forms\UserForm;
use app\entities\User;
use app\entities\ConferenceParticipant;
use app\forms\LoginForm;

class UserComponent
{
    /**
     * @param UserForm $form
     * @return User
     * @throws \Exception
     */
    public static function userSignup(UserForm $form)
    {
        $password = $form->scenario == UserForm::SCENARIO_REGISTER ? $form->password : Yii::$app->security->generateRandomString(8);

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

        if ($form->conference) {
            UserComponent::registerParticipant($form);
        }

        if ($form->scenario == UserForm::SCENARIO_REGISTER) {
            $loginForm = new LoginForm();

            $loginForm->phone = $form->phone;
            $loginForm->password = $form->password;

            LoginComponent::login($loginForm);
        }

        UserComponent::assignRole($form->role, $form->phone);

        SendMailComponent::sendMail($form->email,'<p>Логин: ' . $form->phone . '</p><p>Пароль: ' . $password . '</p>');

        return $user;
    }

    /**
     * @param UserForm $form
     * @param User $user
     * @throws \Exception
     * @return User
     */
    public static function userUpdate(UserForm $form, User $user)
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
    public static function registerParticipant(UserForm $form)
    {
        $user = User::findOne([
            'phone' => $form->phone
        ]);

        $participant = ConferenceParticipant::findOne([
            'user_id' => $user->id,
            'conference_id' => $form->conference
        ]);

        if ($participant) {
            return [
                'status'  => 'success',
                'message' => 'Этот пользователь уже зарегистрирован на конференцию - ' . $participant->conference->title
            ];
        }

        $participant = new ConferenceParticipant();

        $participant->user_id = $user->id;
        $participant->conference_id = $form->conference;

        return $participant->save() ? [
            'status'  => 'success',
            'message' => 'Пользователь успешно зарегистрирован на конференцию - ' . $participant->conference->title
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
    public static function assignRole($role, $user_phone)
    {
        $user = User::findByPhone($user_phone);

        $userRole = Yii::$app->authManager->getRole($role);

        return Yii::$app->authManager->assign($userRole, $user->id) ? true : false;
    }
}
