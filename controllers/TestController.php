<?php

namespace app\controllers;

use yii\web\Controller;
use app\entities\mongodb\Test;

/**
 * Class TestController
 */
class TestController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $test = Test::find()->all();

        return $this->render('index', [
            'test' => $test
        ]);
    }
}
