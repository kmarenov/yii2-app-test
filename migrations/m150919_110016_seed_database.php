<?php

use Faker\Factory;
use yii\db\Migration;

class m150919_110016_seed_database extends Migration
{
    public function up()
    {
        $faker = Factory::create();

        define('TEACHERS_COUNT', 10000);
        define('STUDENTS_COUNT', 100000);
        define('GENDERS_COUNT', 2);
        define('LEVELS_COUNT', 6);
        define('MAX_RELATIONS_COUNT', 50);

        //количество учителей
        $teacherCountCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM teacher');

        //количество учителей c заданным номером телефона
        $teacherCountByPhoneCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM teacher WHERE phone = :phone');

        //количество учеников у учителя
        $teacherStudentCountByTeacherIdCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM teacher_student WHERE teacher_id = :teacher_id');

        //количество связей учителя с заданным учеником
        $teacherStudentCountByTeacherIdStudentIdCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM teacher_student WHERE teacher_id = :teacher_id AND student_id = :student_id');

        //количество учеников
        $studentCountCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM student');

        //количество учеников с заданным email
        $studentCountByEmailCommand =
            Yii::$app->db->createCommand('SELECT COUNT(id) as cnt FROM student WHERE email = :email');


        //заполняем таблицу учителей
        while ($teacherCountCommand->queryOne()['cnt'] < TEACHERS_COUNT) {

            $phone = $faker->phoneNumber;

            if ($teacherCountByPhoneCommand->bindValue('phone', $phone)->queryOne()['cnt'] == 0) {

                $this->insert('teacher', [
                    'name' => $faker->name,
                    'gender' => $faker->numberBetween(1, GENDERS_COUNT),
                    'phone' => $phone,
                ]);

                $teacher_id = Yii::$app->db->getLastInsertID();

                $relationsCount = rand(0, MAX_RELATIONS_COUNT);

                //создаем связи учителей с учениками
                while ($teacherStudentCountByTeacherIdCommand->bindValue('teacher_id',
                        $teacher_id)->queryOne()['cnt'] < $relationsCount) {

                    $attributes = [
                        'teacher_id' => $teacher_id,
                        'student_id' => $faker->numberBetween(1, STUDENTS_COUNT)
                    ];

                    if ($teacherStudentCountByTeacherIdStudentIdCommand->bindValues($attributes)->queryOne()['cnt'] == 0) {
                        $this->insert('teacher_student', $attributes);
                    }
                }
            }
        }

        //заполняем таблицу учеников
        while ($studentCountCommand->queryOne()['cnt'] < STUDENTS_COUNT) {

            $email = $faker->email;

            if ($studentCountByEmailCommand->bindValue('email', $email)->queryOne()['cnt'] == 0) {
                $this->insert('student', [
                    'name' => $faker->name,
                    'email' => $email,
                    'birthdate' => $faker->date(),
                    'level' => $faker->numberBetween(1, LEVELS_COUNT)
                ]);
            }
        }
    }

    public function down()
    {
        $this->truncateTable('teacher');
        $this->truncateTable('student');
        $this->truncateTable('teacher_student');
    }
}
