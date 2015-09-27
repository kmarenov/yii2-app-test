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

        $teacherId = 0;
        $phones = [];
        $relations = [];
        while ($teacherId < TEACHERS_COUNT) {

            $phone = $faker->phoneNumber;

            if (!in_array($phone, $phones)) {

                $this->insert('teacher', [
                    'name' => $faker->name,
                    'gender' => $faker->numberBetween(1, GENDERS_COUNT),
                    'phone' => $phone,
                ]);

                $teacherId++;
                $phones[] = $phone;

                $relationsTotalCnt = mt_rand(0, MAX_RELATIONS_COUNT);

                $relationsCnt = 0;
                $relations[$teacherId] = [];
                while ($relationsCnt < $relationsTotalCnt) {

                    $studentId = $faker->numberBetween(1, STUDENTS_COUNT);

                    if (!in_array($studentId, $relations[$teacherId])) {
                        $this->insert('teacher_student', [
                            'teacher_id' => $teacherId,
                            'student_id' => $studentId
                        ]);

                        $relationsCnt++;
                        $relations[$teacherId][] = $studentId;
                    }
                }
            }
        }

        unset($phones, $relations);

        $studentId = 0;
        $emails = [];
        while ($studentId < STUDENTS_COUNT) {

            $email = $faker->email;

            if (!in_array($email, $emails)) {
                $this->insert('student', [
                    'name' => $faker->name,
                    'email' => $email,
                    'birthdate' => $faker->date(),
                    'level' => $faker->numberBetween(1, LEVELS_COUNT)
                ]);

                $emails[] = $email;
                $studentId++;
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
