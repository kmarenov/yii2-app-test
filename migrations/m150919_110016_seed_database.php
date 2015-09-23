<?php

use Faker\Factory;
use yii\db\Migration;
use yii\db\Query;

class m150919_110016_seed_database extends Migration
{
    public function up()
    {
        $faker = Factory::create();
        $query = new Query();

        define('TEACHERS_COUNT', 10);
        define('STUDENTS_COUNT', 100);
        define('GENDERS_COUNT', 2);
        define('LEVELS_COUNT', 6);
        define('RELATIONS_APPROX_COUNT', 20);

        for ($i = 1; $i <= TEACHERS_COUNT; $i++) {
            $this->insert('teacher', [
                'name' => $faker->name,
                'gender' => $faker->numberBetween(1, GENDERS_COUNT),
                'phone' => $faker->phoneNumber,
            ]);
        }

        for ($i = 1; $i <= STUDENTS_COUNT; $i++) {
            $this->insert('student', [
                'name' => $faker->name,
                'email' => $faker->email,
                'birthdate' => $faker->date(),
                'level' => $faker->numberBetween(1, LEVELS_COUNT)
            ]);
        }

        for ($i = 1; $i <= TEACHERS_COUNT * RELATIONS_APPROX_COUNT; $i++) {
            $attributes = [
                'teacher_id' => $faker->numberBetween(1, TEACHERS_COUNT),
                'student_id' => $faker->numberBetween(1, STUDENTS_COUNT)
            ];

            $rows = $query
                ->select('id')
                ->from('teacher_student')
                ->where($attributes)
                ->all();

            if (empty($rows)) {
                $this->insert('teacher_student', $attributes);
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
