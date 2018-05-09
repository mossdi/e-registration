<?php

namespace app\controllers;

use app\entities\UserToConference;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\entities\Conference;
use app\entities\ConferenceSearch;
use app\forms\ConferenceForm;
use app\components\ConferenceComponent;

/**
 * ConferenceController implements the CRUD actions for Conference model.
 */
class ConferenceController extends Controller
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
                    'delete' => ['POST'],
                    'delete-user-to-conference' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Conference
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Conference model.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Create new conference
     *
     * @param null $id
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function actionCreateForm($id = null)
    {
        $form = new ConferenceForm($id);

        return $this->renderAjax('create', [
            'model' => $form,
        ]);
    }

    /**
     * Conference update
     *
     * @param integer $id
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
    public function actionUpdateForm($id)
    {
        $form = new ConferenceForm($id);

        return $this->renderAjax('update', [
            'model' => $form,
        ]);
    }

    /**
     * Conference form validate
     *
     * @throws \yii\base\InvalidConfigException
     * @return array
     */
    public function actionFormValidate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new ConferenceForm();

        $form->load(Yii::$app->request->post());

        return ActiveForm::validate($form);
    }

    /**
     * Conference create
     *
     * @param $id null
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function actionCreate($id = null)
    {
        $form = new ConferenceForm($id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ((new ConferenceComponent())->conferenceCreate($form)) {
                Yii::$app->session->setFlash('success', 'Событие успешно зарегистрировано в системе!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Событие не зарегистрировано. Обратитесь к администратору системы.');
            }

            $this->redirect(
                '/site/index'
            );
        }
    }

    /**
     * Conference update
     *
     * @param integer $id
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $form = new ConferenceForm($id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ((new ConferenceComponent())->conferenceUpdate($form, Conference::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Событие успешно обновлено!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Событие не обновлено. Обратитесь к администратору системы.');
            }

            $this->redirect(
                '/conference/index'
            );
        }
    }

    /**
     * Deletes an existing Conference model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['deleted' => 1]);

        return $this->redirect(['/conference/index']);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionUserToConference($id)
    {
        $students = new ActiveDataProvider([
            'query' => UserToConference::find()
                ->with(['user'])
                ->where(['conference_id' => $id]),
            'pagination' => false,
        ]);

        return $this->renderAjax('students_list', [
            'students' => $students,
        ]);
    }

    /**
     * @param $user_id
     * @param $conference_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteUserToConference($user_id, $conference_id)
    {
        $studentToConference = UserToConference::findOne([
            'user_id' => $user_id,
            'conference_id' => $conference_id,
        ]);

        if ($studentToConference->delete()) {
            Yii::$app->session->setFlash('success', 'Пользователь удален с конференции!');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка! Пользователь не удален с конференции. Обратитесь к администратору системы.');
        }

        $this->redirect([
            '/conference/index'
        ]);
    }

    /**
     * Finds the Conference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Conference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conference::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
