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

        define('TEACHERS_COUNT', 10000);
        define('STUDENTS_COUNT', 100000);
        define('GENDERS_COUNT', 2);
        define('LEVELS_COUNT', 6);
        define('MAX_RELATIONS_COUNT', 50);

        for ($i = 1; $i <= TEACHERS_COUNT; $i++) {
            $this->insert('teacher', [
                'name' => $faker->name,
                'gender' => $faker->numberBetween(1, GENDERS_COUNT),
                'phone' => $faker->phoneNumber,
            ]);

            $relationsCount = rand(0, MAX_RELATIONS_COUNT);

            for ($j = 1; $j <= $relationsCount; $j++) {
                $attributes = [
                    'teacher_id' => $i,
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

        for ($i = 1; $i <= STUDENTS_COUNT; $i++) {
            $this->insert('student', [
                'name' => $faker->name,
                'email' => $faker->email,
                'birthdate' => $faker->date(),
                'level' => $faker->numberBetween(1, LEVELS_COUNT)
            ]);
        }
    }

    public function down()
    {
        $this->truncateTable('teacher');
        $this->truncateTable('student');
        $this->truncateTable('teacher_student');
    }
}
