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
                    'students_count' =>
                        [
                            'students',
                            'get' => function ($value) {
                                return count($value);
                            }
                        ]
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
            [['gender'], 'integer', 'min' => 1, 'max' => 2],
            [['name', 'phone'], 'string', 'max' => 255],
            ['students_list', 'each', 'rule' => ['integer']],
            [
                'phone',
                'match',
                'pattern' => '/^[0-9+\(\)#\.\s\/ext-]+$/',
                'message' => 'Значение «Номер телефона» не является правильным номером телефона.'
            ],
            [['phone'], 'unique']
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
            'students_count' => 'Количество учеников',
        ];
    }

    public function getGenderName()
    {
        return static::getGendersList()[$this->gender];
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
