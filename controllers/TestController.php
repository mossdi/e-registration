<?php

namespace app\controllers;

use Yii;
use app\entities\Question;
use app\entities\Conference;
use yii\web\Controller;


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
        return $this->render('view', [
            'questions' => Question::findAll(['conference_id' => $id]),
            'conference' => Conference::findOne($id),
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAddQuestion($id)
    {
        $question = new Question();

        if ($question->load(Yii::$app->request->post()) && $question->validate()) {
            $question->conference_id = $id;

            $question->save();

            return $this->redirect([
                '/test/view?id=' . $id
            ]);
        }

        return $this->renderAjax('/test/question_form', [
            'model' => $question,
        ]);
    }
}
