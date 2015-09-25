<?php

namespace app\controllers;

use app\models\Student;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
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
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Ученики';

        $dataProvider = new ActiveDataProvider([
            'query' => Student::find(),
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
     * Displays a single Student model.
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
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Student();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Student model.
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
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTeachersWithMaxCommonStudents()
    {
        $title = 'Имена любых двух учителей, у которых максимальное количество общих учеников, и список этих общих учеников';

        $teachers = Yii::$app->getDb()->createCommand('
            SELECT
            t1.id AS tid1,
            t1.name AS tname1,
            t2.id AS tid2,
            t2.name AS tname2,
            s.common AS common
            FROM teacher t1
                INNER JOIN teacher t2 ON t1.id < t2.id
                LEFT JOIN (
                    SELECT DISTINCT
                        ts1.teacher_id AS t1_id,
                        ts2.teacher_id AS t2_id,
                        COUNT(ts1.student_id) AS common
                    FROM teacher_student ts1, teacher_student ts2
                    WHERE ts1.student_id = ts2.student_id
                        AND ts1.teacher_id < ts2.teacher_id
                    GROUP BY ts1.teacher_id, ts2.teacher_id
                ) s
                ON (s.t1_id = t1.id AND s.t2_id = t2.id)
            ORDER BY common DESC LIMIT 1
        ')->query()->read();

        $common_students_sql = '
            SELECT s.id,
                   s.name,
                   s.birthdate,
                   s.email,
                   s.level
            FROM student s
            WHERE s.id IN
                (SELECT DISTINCT ts1.student_id
                 FROM teacher_student ts1,
                      teacher_student ts2
                 WHERE ts1.student_id = ts2.student_id
                   AND ts1.teacher_id =:tid1
                   AND ts2.teacher_id = :tid2)
        ';

        $dataProvider = new ActiveDataProvider([
            'query' => Student::findBySql($common_students_sql,
                array_intersect_key($teachers, array_flip(['tid1', 'tid2']))),
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_ASC]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title,
            'teachers' => $teachers
        ]);
    }
}
