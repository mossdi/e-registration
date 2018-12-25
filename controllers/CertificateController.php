<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\services\certificate\CertificateService;
use app\forms\CertificateForm;
use app\entities\User;
use app\entities\Certificate;
use app\entities\CertificateSearch;

/**
 * CertificateController implements the CRUD actions for Certificate model.
 */
class CertificateController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN]
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Certificate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CertificateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Certificate model.
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
     * Updates an existing Certificate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateForm($id)
    {
        $form = new CertificateForm($id);

        return $this->renderAjax('update', [
            'model' => $form,
        ]);
    }

    /**
     * Certificate form validate
     *
     * @throws \yii\base\InvalidConfigException
     * @return array
     */
    public function actionFormValidate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new CertificateForm();

        $form->load(Yii::$app->request->post());

        return ActiveForm::validate($form);
    }

    /**
     * Conference update
     *
     * @param integer $id
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $form = new CertificateForm($id);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (CertificateService::certificateUpdate($form, Certificate::findOne($id))) {
                Yii::$app->session->setFlash('success', 'Сертификат успешно обновлен!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Сертификат не обновлен. Обратитесь к администратору системы.');
            }

            $this->redirect(
                '/certificate/index'
            );
        }
    }

    /**
     * Deletes an existing Certificate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['deleted' => 0]);

        return $this->redirect([
            '/certificate/index'
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionDownload($id)
    {
        return CertificateService::certificateDownload($id);
    }

    /**
     * Finds the Certificate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Certificate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Certificate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
