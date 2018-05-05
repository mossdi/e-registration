<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use app\components\UserComponent;
use app\components\LoginComponent;
use app\entities\User;
use app\forms\LoginForm;
use app\forms\UserForm;
use app\entities\UserSearch;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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
            if ((new LoginComponent())->login($form)) {
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
     * @return array|string
     */
    public function actionSignupForm($id = null)
    {
        $form = new UserForm($id);

        return $this->renderAjax('signup', [
            'model' => $form,
            'user_flag' => $id != null ? true : false
        ]);
    }

    /**
     * Update form
     *
     * @param $id
     * @return string
     */
    public function actionUpdateForm($id)
    {
        $form = new UserForm($id);

        return $this->renderAjax('update', [
                'model' => $form
            ]
        );
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

        if ($scenario == 'create') {
            $form->scenario = UserForm::SCENARIO_CREATE;
        }

        $form->load(Yii::$app->request->post());

        return ActiveForm::validate($form);
    }

    /**
     * SignUp user
     *
     * @throws \Exception
     */
    public function actionSignup()
    {
        $form = new UserForm();
        $form->scenario = UserForm::SCENARIO_CREATE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ((new UserComponent())->userSignup($form)) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно зарегистрирован в системе.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка. Пользователь не зарегистрирован. Обратитесь к администратору системы.');
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
            if ((new UserComponent())->userUpdate($form, User::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка. Пользователь не обновлен. Обратитесь к администратору системы.');
            };

            $this->redirect(
                '/site/index'
            );
        }
    }

    /**
     * SignUp user to conference
     *
     * @throws \Exception
     */
    public function actionSignupConference()
    {
        $form = new UserForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $result = (new UserComponent())->singupToConference($form);

            Yii::$app->session->setFlash($result['status'], $result['message']);

            $this->redirect(
                '/site/index'
            );
        }
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
              ->filterWhere(['like', 'first_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'last_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'patron_name', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'passport', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'phone', Yii::$app->request->get('term')])
            ->orFilterWhere(['like', 'email', Yii::$app->request->get('term')])
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
