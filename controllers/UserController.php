<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\components\UserComponent;
use app\components\LoginComponent;
use app\forms\LoginForm;
use app\forms\UserForm;
use app\entities\User;
use app\entities\Conference;
use app\entities\UserSearch;

/**
 * Class UserController
 * @package app\controllers
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can(User::ROLE_RECEPTIONIST)) {
                return $this->actionSignupForm($id = null, UserForm::SCENARIO_CREATE_PAGE, $clearForm = false);
            }

            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (LoginComponent::login($form)) {
                $this->refresh();
            };
        }

        $form->password = '';

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logout action.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * SignUp form
     *
     * @param null $id
     * @param $scenario
     * @param bool $clearForm
     * @return array|string
     */
    public function actionSignupForm($id = null, $scenario, $clearForm = false)
    {
        $form = new UserForm($id);
        $form->scenario = $scenario;

        $conference = Conference::find()
               ->where(['<=', '(start_time - ' . Yii::$app->setting->get('registerOpen') .')', time()])
            ->andWhere(['is', 'end_time', null])
            ->andWhere(['status' => Conference::STATUS_ACTIVE,])
            ->andWhere(['deleted' => 0])
             ->orderBy(['start_time' => SORT_ASC])
                 ->one();

        return $scenario == UserForm::SCENARIO_CREATE_PAGE && !$clearForm ?
            $this->render('signup', [
                'model' => $form,
                'conference' => $conference,
            ]) :
            $this->renderAjax('signup', [
                'model' => $form,
                'conference' => $conference,
            ]);
    }

    /**
     * User form validate
     *
     * @param string $scenario
     * @return array;
     */
    public function actionFormValidate($scenario)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new UserForm();
        $form->scenario = $scenario;

        $form->load(Yii::$app->request->post());

        return ActiveForm::validate($form);
    }

    /**
     * SignUp user
     *
     * @param $scenario
     * @return array|string
     * @throws \Exception
     */
    public function actionSignup($scenario)
    {
        $form = new UserForm();
        $form->scenario = $scenario;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE) {
                return $this->actionRegisterParticipant($form->id, $form->conference, Conference::LEARNING_FULL_TIME, $scenario);
            }

            if (UserComponent::userSignup($form)) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно зарегистрирован в системе!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не зарегистрирован. Обратитесь к администратору системы.');
            };
            
            return $this->actionSignupForm(null, UserForm::SCENARIO_CREATE_PAGE, true);
        }
    }

    /**
     * SignUp user to conference
     *
     * @param $user_id
     * @param $conference_id
     * @param $method
     * @param null $scenario
     * @return array|string
     * @throws \Exception
     */
    public function actionRegisterParticipant($user_id, $conference_id, $method, $scenario = null)
    {
        $result = UserComponent::registerParticipant($user_id, $conference_id, $method);

        Yii::$app->session->setFlash($result['status'], $result['message']);

        return $this->actionSignupForm(null, UserForm::SCENARIO_CREATE_PAGE, true);
    }

    /**
     * Update user
     *
     * @param $id
     * @param null $scenario
     * @return bool|Response
     * @throws \Exception
     */
    public function actionUpdate($id, $scenario)
    {
        $form = new UserForm($id);
        $form->scenario = $scenario;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (UserComponent::userUpdate($form, User::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не обновлен. Обратитесь к администратору системы.');
            };

            if (Yii::$app->request->isPjax) {
                return $this->actionSignupForm($id, $scenario == UserForm::SCENARIO_UPDATE ? $scenario : UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE);
            } elseif (!Yii::$app->request->isAjax) {
                return $this->redirect(
                    '/site/index'
                );
            }
        }
    }

    /**
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        User::findOne($id)->updateAttributes(['deleted' => 1]);

        return $this->redirect([
            '/user/index'
        ]);
    }

    /**
     * FindUser action
     *
     * @return mixed
     */
    public function actionAutocomplete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $inputRequest = explode(' ', Yii::$app->request->get('term'));

        $cleanRequest = [];

        //delete empty element
        foreach ($inputRequest as $requestPart) {
            if (!empty(trim($requestPart))) {
                $cleanRequest[] = trim($requestPart);
            }
        }

        $results = User::find();

        //foreach ($request as $word) {
        //    $results->andWhere('(last_name LIKE \'' . $word . '%\' OR first_name LIKE \'' . $word . '%\' OR patron_name LIKE \'' . $word . '%\' )');
        //};

        if (!empty($cleanRequest[0])) {
            $results->andWhere('last_name LIKE \'' . $cleanRequest[0] . '%\'');
        }
        if (!empty($cleanRequest[1])) {
            $results->andWhere('first_name LIKE \'' . $cleanRequest[1] . '%\'');
        }
        if (!empty($cleanRequest[2])) {
            $results->andWhere('patron_name LIKE \'' . $cleanRequest[2] . '%\'');
        }

        $results->andWhere(['deleted' => 0]);

        $users = [];

        if ($results) {
            foreach ($results->all() as $user):
                $users[] = [
                    'id'    => $user->id,
                    'value' => $user->last_name . ' ' . $user->first_name . ' ' . $user->patron_name . (!empty($user->post) ? ' (' . $user->post . ')' : ''),
                    'label' => $user->last_name . ' ' . $user->first_name . ' ' . $user->patron_name . (!empty($user->post) ? ' (' . $user->post . ')' : ''),
                ];
            endforeach;
        }

        return $users;
    }
}
