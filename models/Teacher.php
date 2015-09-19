<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gender
 * @property string $phone
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'gender'], 'required'],
            [['gender'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'gender' => 'Пол',
            'phone' => 'Номер телефона',
        ];
    }

    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'student_id'])
            ->viaTable('teacher_student', ['teacher_id' => 'id']);
    }
}
