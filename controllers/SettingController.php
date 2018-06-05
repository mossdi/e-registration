<?php

namespace app\controllers;

use Yii;
use app\entities\Setting;
use yii\web\Controller;
use yii\base\Model;

/**
 * Class SettingController
 * @package app\controllers
 */
class SettingController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $settings = Setting::find()->indexBy('param')->all();

        return $this->render('index', [
                'settings' => $settings
            ]
        );
    }

    /**
     * @return string
     */
    public function actionUpdate()
    {
        $settings = Setting::find()->indexBy('param')->all();

        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {
            foreach ($settings as $setting) {
                $setting->save(false);
            }

            Yii::$app->session->setFlash('success', 'Настройки успешно обновлены!');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка! Настройки не обновлены!');
        }

        return $this->render('index', [
                'settings' => $settings
            ]
        );
    }
}