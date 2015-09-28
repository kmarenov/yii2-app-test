<?php

use app\models\Student;
use app\models\Teacher;
use kartik\select2\Select2;
use unclead\widgets\MultipleInput;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Teacher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList(Teacher::getGendersList()) ?>

    <?= $form->field($model, 'phone') ?>

    <?php
    $studentsList = Student::find()->select(['id', 'name as text'])->where(['id' => $model->students_list])->orderBy('text')->asArray()->all();

    $data = [];
    $studentsListIdsByName = [];

    foreach ($studentsList as $student) {
        $data[$student['id']] = $student;
        $studentsListIdsByName[] = $student['id'];
    }

    $model->students_list = $studentsListIdsByName;

    $data = Json::encode($data);

    echo $form->field($model, 'students_list')->widget(MultipleInput::className(), [
        'columns' => [
            [
                'name' => 'students_list',
                'type' => Select2::className(),
                'options' => [
                    'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'allowClear' => true,
                        'ajax' => [
                            'url' => Url::to(['student/list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                            'results' => new JsExpression('
                                function (data) {
                                            return {
                                                results: $.map(data, function (item) {
                                                    return {
                                                        text: item.name,
                                                        id: item.id
                                                    }
                                                })
                                            };
                                        }
                            ')
                        ],
                        'initSelection' => new JsExpression('
                            function (element, callback) {
                                var id = element.val();
                                if(id)
                                {
                                    var data = ' . $data . ';
                                    callback(data[id]);
                                }
                            }
                        '),
                    ],
                ],
            ]
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
