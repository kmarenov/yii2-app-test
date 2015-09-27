<?php

use app\models\Teacher;
use unclead\widgets\MultipleInput;
use yii\helpers\Html;
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

    //$data = JSON::encode(Student::find()->select(['id', 'name'])->where(['id' => $model->students_list])->orderBy('name')->asArray()->all());

    echo $form->field($model, 'students_list')->widget(MultipleInput::className(), [
//        'columns' => [
//            [
//                'name'  => 'students_list',
//                'type'  => \kartik\select2\Select2::className(),
//                'value' => new JsExpression($data),
//                'options' => [
//                    //'initValueText' => new JsExpression('function(param) { return {q:params.term}; }'),
//                    'pluginOptions' => [
//                        //'allowClear' => true,
//                        'minimumInputLength' => 3,
//                        'ajax' => [
//                            'url' =>  \yii\helpers\Url::to(['student/list']),
//                            'dataType' => 'json',
//                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
//                            'results' => new JsExpression('
//                                function (data) {
//                                            return {
//                                                results: $.map(data, function (item) {
//                                                    return {
//                                                        text: item.name,
//                                                        id: item.id
//                                                    }
//                                                })
//                                            };
//                                        }
//                            ')
//                        ],
//                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
//                        //'templateResult' => new JsExpression('function(student) { return student.text; }'),
//                        //'templateSelection' => new JsExpression('function (student) { return student.text; }'),
//                    ],
//                ],
//            ]
//        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
