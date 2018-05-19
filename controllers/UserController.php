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
     * @return array|string
     */
    public function actionSignupForm($id = null, $scenario)
    {
        $form = new UserForm($id);
        $form->scenario = $scenario;

        $conference = Conference::find()
               ->where(['>=', '(start_time + 1800)', time()])
            ->andWhere(['status' => Conference::STATUS_ACTIVE,])
            ->andWhere(['deleted' => 0])
             ->orderBy(['start_time' => SORT_ASC])
                 ->all();

        return $this->renderAjax('signup', [
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
     * @return Response
     * @throws \Exception
     */
    public function actionSignup($scenario)
    {
        $form = new UserForm();
        $form->scenario = $scenario;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($scenario == UserForm::SCENARIO_REGISTER_PARTICIPANT) {
                return $this->actionRegisterParticipant($form->id, $form->conference, Conference::LEARNING_FULL_TIME);
            }

            if (UserComponent::userSignup($form)) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно зарегистрирован в системе!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не зарегистрирован. Обратитесь к администратору системы.');
            };

            $this->redirect(
                '/site/index'
            );
        }
    }

    /**
     * Update user
     *
     * @param $id
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $form = new UserForm($id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (UserComponent::userUpdate($form, User::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не обновлен. Обратитесь к администратору системы.');
            };

            $this->redirect(
                '/user/index'
            );
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
     * SignUp user to conference
     *
     * @param $user_id
     * @param $conference_id
     * @param $method
     * @throws \Exception
     */
    public function actionRegisterParticipant($user_id, $conference_id, $method)
    {
        $result = UserComponent::registerParticipant($user_id, $conference_id, $method);

        Yii::$app->session->setFlash($result['status'], $result['message']);

        $this->redirect(
            '/site/index'
        );
    }

    /**
     * FindUser action
     *
     * @return mixed
     */
    public function actionAutocomplete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $users = [];

        $results = User::find()
            ->orFilterWhere(['like', 'first_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'last_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'patron_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'passport', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'phone', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'email', Yii::$app->request->get('term')])
                 ->andWhere(['deleted' => 0])
                      ->all();

        if ($results) {
            foreach ($results as $user):
                $users[] = [
                    'value' => $user->id,
                    'label' => $user->last_name . ' ' . $user->first_name . ' ' . $user->patron_name . ' ('. $user->passport .' / ' . $user->phone . ')'
                ];
            endforeach;
        }

        return $users;
    }
}
