<?php

namespace app\controllers;

use Yii;
use app\entities\Question;
use app\entities\Conference;
use app\entities\Answer;
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

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdateQuestion($id)
    {
        $question = Question::findOne($id);

        if ($question->load(Yii::$app->request->post()) && $question->save()) {
            return $this->redirect([
                '/test/view?id=' . $question->conference_id
            ]);
        }

        return $this->renderAjax('/test/question_form', [
            'model' => $question,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $question = Question::findOne($id);

        $question->delete();

        return $this->redirect([
            '/test/view?id=' . $question->conference_id
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAddAnswer($id)
    {
        $answer = new Answer();

        if ($answer->load(Yii::$app->request->post()) && $answer->validate()) {
            $answer->question_id = $id;

            $answer->save();

            return $this->redirect([
                '/test/view?id=' . Question::findOne($id)->conference_id
            ]);
        }

        return $this->renderAjax('/test/answer_form', [
            'model' => $answer,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdateAnswer($id)
    {
        $answer = Answer::findOne($id);
        $question = Question::findOne(['id' => $answer->question_id]);

        if ($answer->load(Yii::$app->request->post()) && $answer->save()) {
            return $this->redirect([
                '/test/view?id=' . $question->conference_id,
            ]);
        }

        return $this->renderAjax('/test/answer_form', [
            'model' => $answer,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAnswer($id)
    {
        $answer = Answer::findOne($id);
        $question = Question::findOne(['id' => $answer->question_id]);

        $answer->delete();

        return $this->redirect([
            '/test/view?id=' . $question->conference_id,
        ]);
    }
}
