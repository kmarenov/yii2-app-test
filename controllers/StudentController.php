<?php

namespace app\controllers;

use app\models\Student;
use app\models\Teacher;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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

    /**
     * Имена любых двух учителей, у которых максимальное количество общих учеников, и список этих общих учеников.
     * @return mixed
     */
    public function actionTeachersWithMaxCommonStudents()
    {
        $title = 'Имена любых двух учителей, у которых максимальное количество общих учеников, и список этих общих учеников';

        $twoTeachersMaxStudents = Teacher::getTwoHasMaxCommonStudents();

        $teachers = Teacher::find()->where(['id' => $twoTeachersMaxStudents])->asArray()->all();

        $teachersNames = ArrayHelper::getColumn($teachers, 'name');

        $dataProvider = new ActiveDataProvider([
            'query' => Student::commonFromTwoTeachers($twoTeachersMaxStudents)
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_ASC]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => $title,
            'teachersNames' => $teachersNames
        ]);
    }

    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $data = Student::find()->select('id, name AS text')
                ->where('name LIKE "%' . $q . '%"')
                ->limit(20)->asArray()->all();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Student::find($id)->name];
        }
        return $out;
    }
}
