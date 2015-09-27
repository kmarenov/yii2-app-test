<?php

namespace app\models;

use voskobovich\behaviors\ManyToManyBehavior;
use Yii;
use yii\helpers\ArrayHelper;

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
    //количество учеников у каждого учителя
    const studentsCountSql = '
            SELECT DISTINCT ts.teacher_id AS tid,
                    COUNT(ts.student_id) AS cnt
            FROM teacher_student ts
            GROUP BY ts.teacher_id
    ';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * Список учителей, с которыми занимаются только ученики, родившиеся в апреле.
     * @return \yii\db\Query
     */
    public static function teachsOnlyAprilBornStudents()
    {
        //учителя, с которыми занимаются только ученики, родившиеся в апреле
        $teachersAprilStudentsSql = '
          SELECT DISTINCT ts1.teacher_id AS tid
                 FROM teacher_student ts1
                 WHERE ts1.student_id IN
                     (SELECT s.id
                      FROM student s
                      WHERE MONTH(s.birthdate) = 4)
                 GROUP BY ts1.teacher_id
                 HAVING COUNT(ts1.teacher_id) =
                   ( SELECT DISTINCT COUNT(ts2.teacher_id)
                    FROM teacher_student ts2
                    WHERE ts1.teacher_id = ts2.teacher_id)
          ';

        $teachersAprilStudents = Yii::$app->db->createCommand($teachersAprilStudentsSql)->query()->readAll();

        $teachersAprilStudentsIds = ArrayHelper::getColumn($teachersAprilStudents, function ($element) {
            return $element['tid'];
        });

        return self::find()->select('id, name, gender, phone, s.cnt as stud_cnt')->where([
            'in',
            'id',
            $teachersAprilStudentsIds
        ])->join('LEFT JOIN', '(' . self::studentsCountSql . ') s', 's.tid = id');
    }

    /**
     * Два учителя, у которых максимальное количество общих учеников
     * @return array
     */
    public static function getTwoHasMaxCommonStudents()
    {
        $twoTeachersMaxStudentsSql = '
            SELECT DISTINCT
                ts1.teacher_id AS tid1,
                ts2.teacher_id AS tid2,
                COUNT(ts1.student_id) AS common
            FROM teacher_student ts1, teacher_student ts2
            WHERE ts1.student_id = ts2.student_id
                AND ts1.teacher_id < ts2.teacher_id
            GROUP BY ts1.teacher_id, ts2.teacher_id
            ORDER BY common DESC LIMIT 1
        ';

        $twoTeachersMaxStudents = Yii::$app->db->createCommand($twoTeachersMaxStudentsSql)->query()->read();

        return [$twoTeachersMaxStudents['tid1'], $twoTeachersMaxStudents['tid2']];
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
            [
                'students_list',
                'each',
                'rule' => [
                    'exist',
                    'targetClass' => Student::className(),
                    'targetAttribute' => 'id',
                    'message' => 'Значение «Ученики» содержит ID несуществующего ученика.'
                ],
            ],
            [
                'phone',
                'match',
                'pattern' => '/^[0-9+\(\)#\.\s\/ext-]+$/',
                'message' => 'Значение «Номер телефона» не является правильным номером телефона.'
            ],
            [['phone'], 'unique'],
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
