<?php

use app\models\Student;
use app\models\Teacher;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Teacher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList(Teacher::getGendersList()) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::classname(), ['mask' => '+79999999999']) ?>

    <?= $form->field($model, 'students_list')->widget(kartik\select2\Select2::className(),
        [
            'data' => ArrayHelper::map(Student::find()->orderBy('name')->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Выберите учеников ...', 'multiple' => true],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
