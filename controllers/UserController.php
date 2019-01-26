<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\services\user\UserService;
use app\services\user\LoginService;
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
            if (Yii::$app->user->can(User::ROLE_RECEPTIONIST) || Yii::$app->user->can(User::ROLE_RECEPTIONIST_CURATOR)) {
                return $this->redirect([
                    '/user/signup-form?scenario=create-page'
                ]);
            }

            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (LoginService::login($form)) {
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
    public function actionSignupForm($scenario, $id = null, $clearForm = false)
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
                return $this->actionRegisterParticipant($form->id, $form->conference, Conference::LEARNING_FULL_TIME);
            }

            if (UserService::userSignup($form)) {
                Yii::$app->session->setFlash('success', $form->conference ? 'Пользователь успешно зарегистрирован на конференцию - ' . Conference::findOne($form->conference)->title : 'Пользователь успешно зарегистрирован в системе!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не зарегистрирован. Обратитесь к администратору системы.');
            };

            if ($form->scenario == UserForm::SCENARIO_REGISTER) {
                return $this->goHome();
            }

            return $this->actionSignupForm(UserForm::SCENARIO_CREATE_PAGE, null, true);
        }

        //Если вдруг pJax не дождется ответа и будет ломится в экшн по прямой ссылке
        if (!Yii::$app->request->isPjax && !Yii::$app->request->isAjax) {
            return $this->redirect([
                'user/signup-form?scenario=' . UserForm::SCENARIO_CREATE_PAGE,
            ]);
        }
    }

    /**
     * SignUp user to conference
     *
     * @param $user_id
     * @param $conference_id
     * @param $method
     * @return array|string
     * @throws \Exception
     */
    public function actionRegisterParticipant($user_id, $conference_id, $method)
    {
        $result = UserService::registerParticipant($user_id, $conference_id, $method);

        Yii::$app->session->setFlash($result['status'], $result['message']);

        return $this->actionSignupForm( UserForm::SCENARIO_CREATE_PAGE, null, true);
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
            if (UserService::userUpdate($form, User::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не обновлен. Обратитесь к администратору системы.');
            };

            if (Yii::$app->request->isPjax) {
                return $this->actionSignupForm( $scenario == UserForm::SCENARIO_UPDATE ? $scenario : UserForm::SCENARIO_REGISTER_PARTICIPANT_PAGE, $id);
            } else {
                return $this->redirect(
                    '/site/index'
                );
            }
        }
    }

    /**
     * @param $id
     * @param bool $removal
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $removal = false)
    {
        $user = User::findOne($id);

        if ($removal) {
            $user->delete();
        } else {
            $user->updateAttributes(['deleted' => 1]);
        }

        Yii::$app->session->setFlash('success', 'Пользователь успешно ' . ($removal ? 'удален' : 'заблокирован') . '!');

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
