<?php

namespace app\controllers;

use app\models\Teacher;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Учителя';

        $dataProvider = new ActiveDataProvider([
            'query' => Teacher::find(),
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_ASC]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title
        ]);
    }

    public function actionApril()
    {
        $title = 'Список учителей, с которыми занимаются только ученики, родившиеся в апреле';

        $sql = 'SELECT t.id, t.name, t.gender, t.phone FROM teacher t WHERE t.id IN (
                  SELECT DISTINCT ts1.teacher_id FROM teacher_student ts1
                  WHERE ts1.student_id IN (SELECT s.id FROM student s WHERE MONTH(s.birthdate) = 4)
                  GROUP BY ts1.teacher_id
                  HAVING COUNT(ts1.teacher_id) = (
                    SELECT DISTINCT COUNT(ts2.teacher_id) FROM teacher_student ts2
                    WHERE ts1.teacher_id = ts2.teacher_id)
                )';

        $dataProvider = new ActiveDataProvider([
            'query' => Teacher::findBySql($sql),
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_ASC]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title
        ]);
    }

    /**
     * Displays a single Teacher model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teacher();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
