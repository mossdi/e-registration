<?php

namespace app\controllers;

use yii\web\Controller;
use app\entities\Conference;

/**
 * Class TestController
 */
class TestController extends Controller
{
    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $conference = Conference::findOne($id);

        return $this->render('view', [
            'conference' => $conference,
        ]);
    }

    public function actionAddQuestionForm($id)
    {

    }
}
