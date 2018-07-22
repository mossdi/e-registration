<?php

namespace app\components;

use Yii;
use app\forms\UserForm;
use app\entities\User;
use app\entities\ConferenceParticipant;
use app\forms\LoginForm;
use app\entities\Conference;
use app\entities\Certificate;

/**
 * Class UserComponent
 * @package app\components
 */
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
            $form->organization_branch,
            $form->post,
            $form->speciality,
            $form->email,
            $password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Ошибка создания пользователя');
        }

        if ($form->scenario == UserForm::SCENARIO_REGISTER) {
            $loginForm = new LoginForm();

            $loginForm->email = $form->email;
            $loginForm->password = $form->password;

            LoginComponent::login($loginForm);
        }

        if ($form->conference) {
            UserComponent::registerParticipant($user->id, $form->conference, Conference::LEARNING_FULL_TIME);
        }

        UserComponent::assignRole($form->role, $user->id);

        if (!empty($form->email)) {
            SendMailComponent::sendMail($form->email, Yii::$app->controller->renderPartial('/html_block/mail/access_data', [
                'email' => $form->email, 'password' => $password
            ]));
        }

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
        $user->organization_branch = $form->organization_branch;
        $user->post = $form->post;
        $user->speciality = $form->speciality;
        $user->email = mb_strtolower($form->email);
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
     * @param int $user_id
     * @param int $conference_id
     * @param string $method
     * @throws \Exception
     * @return array|string
     */
    public static function registerParticipant($user_id, $conference_id, $method)
    {
        $conference = Conference::findOne($conference_id);

        if ($conference->end_time != null) {
            return [
                'status'  => 'error',
                'message' => 'Регистрация на конференцию уже закрыта!'
            ];
        }

        $participant = ConferenceParticipant::findOne([
            'user_id' => $user_id,
            'conference_id' => $conference_id
        ]);

        if ($participant) {
            return [
                'status'  => 'success',
                'message' => 'Пользователь успешно зарегистрирован на конференцию - ' . $participant->conference->title
            ];
        }

        $participant = new ConferenceParticipant();

        $participant->user_id = $user_id;
        $participant->conference_id = $conference_id;
        $participant->reseption_id = Yii::$app->user->id;
        $participant->method = $method;

        if ($method == Conference::LEARNING_FULL_TIME){
            $certificate = Certificate::create($user_id, $conference_id, $method);

            $transaction = Yii::$app->db->beginTransaction();

            if ($participant->save() && $certificate->save()) {
                $transaction->commit();

                return [
                    'status'  => 'success',
                    'message' => 'Пользователь успешно зарегистрирован на конференцию - ' . $participant->conference->title
                ];
            } else {
                $transaction->rollBack();

                return [
                    'status'  => 'error',
                    'message' => 'Ошибка! Пользователь не зарегистрирован на конференцию. Обратитесь к администратору системы.'
                ];
            }
        } else {
            return $participant->save() ? [
                'status'  => 'success',
                'message' => 'Пользователь успешно зарегистрирован на конференцию - ' . $participant->conference->title
            ] : [
                'status'  => 'error',
                'message' => 'Ошибка. Пользователь не зарегистрирован на конференцию. Обратитесь к администратору системы.'
            ];
        }
    }

    /**
     * @param $role
     * @param $user_id
     * @throws \Exception
     * @return mixed
     */
    public static function assignRole($role, $user_id)
    {
        $user = User::findOne($user_id);

        $userRole = Yii::$app->authManager->getRole($role);

        return Yii::$app->authManager->assign($userRole, $user->id) ? true : false;
    }
}
