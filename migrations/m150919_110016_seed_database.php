<?php

use Faker\Factory;
use yii\db\Migration;

class m150919_110016_seed_database extends Migration
{
    public function up()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $this->insert('teacher', [
                'name' => $faker->name,
                'gender' => $faker->numberBetween(1, 2),
                'phone' => $faker->phoneNumber,
            ]);
        }

        for ($i = 1; $i <= 100; $i++) {
            $this->insert('student', [
                'name' => $faker->name,
                'email' => $faker->email,
                'birthdate' => $faker->date(),
                'level' => $faker->numberBetween(1, 6)
            ]);
        }

        for ($i = 1; $i <= 1000; $i++) {
            $this->insert('teacher_student', [
                'teacher_id' => $faker->numberBetween(1, 10),
                'student_id' => $faker->numberBetween(1, 100),
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
