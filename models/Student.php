<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "student".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $birthdate
 * @property integer $level
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * Общие ученики двух заданных учителей
     * @param array $teachers
     * @return $this
     */
    public static function commonFromTwoTeachers($teachers)
    {
        $commonStudentsSql = '
                SELECT DISTINCT ts1.student_id
                 FROM teacher_student ts1,
                      teacher_student ts2
                 WHERE ts1.student_id = ts2.student_id
                   AND ts1.teacher_id =:tid1
                   AND ts2.teacher_id = :tid2
        ';

        $commonStudents = Yii::$app->db->createCommand($commonStudentsSql, [
            'tid1' => $teachers[0],
            'tid2' => $teachers[1],
        ])->query()->readAll();

        $commonStudentsIds = ArrayHelper::getColumn($commonStudents, function ($element) {
            return $element['student_id'];
        });

        return self::find()->select('id, name, birthdate, email, level')->where([
            'in',
            'id',
            $commonStudentsIds
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'birthdate'], 'required'],
            [['birthdate'], 'date', 'format' => 'yyyy-mm-dd'],
            [['level'], 'integer', 'min' => 1, 'max' => 6],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['name', 'email'], 'string', 'max' => 255],
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
            'email' => 'E-mail',
            'birthdate' => 'Дата рождения',
            'level' => 'Уровень языка',
        ];
    }

    public function getLevelName()
    {
        return static::getLevelsList()[$this->level];
    }

    public static function getLevelsList()
    {
        return [
            1 => 'A1',
            2 => 'A2',
            3 => 'B1',
            4 => 'B2',
            5 => 'C1',
            6 => 'C2',
        ];
    }

    public function getTeachers()
    {
        return $this->hasMany(Teacher::className(), ['id' => 'teacher_id'])
            ->viaTable('teacher_student', ['student_id' => 'id']);
    }
}
