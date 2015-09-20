<?php

namespace app\models;

use voskobovich\behaviors\ManyToManyBehavior;
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

    public function behaviors()
    {
        return [
            [
                'class' => ManyToManyBehavior::className(),
                'relations' => [
                    'students_list' => 'students',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'gender'], 'required'],
            [['gender'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 255],
            ['students_list', 'safe']
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
            'students_list' => 'Ученики',
        ];
    }

    public function getGenderName()
    {
        return self::getGendersList()[$this->gender];
    }

    public static function getGendersList()
    {
        return [1 => 'Мужской', 2 => 'Женский'];
    }

    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'student_id'])
            ->viaTable('teacher_student', ['teacher_id' => 'id']);
    }
}
