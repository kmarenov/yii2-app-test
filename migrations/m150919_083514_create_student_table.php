<?php

use yii\db\Migration;

class m150919_083514_create_student_table extends Migration
{
    public function up()
    {
        $this->createTable('student', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->unique(),
            'birthdate' => $this->date()->notNull(),
            'level' => $this->smallInteger(),
        ]);

        $this->createIndex('name', 'student', 'name');
        $this->createIndex('birthdate', 'student', 'birthdate');
        $this->createIndex('level', 'student', 'level');
    }

    public function down()
    {
        $this->dropIndex('name', 'student');
        $this->dropIndex('email', 'student');
        $this->dropIndex('birthdate', 'student');
        $this->dropIndex('level', 'student');

        $this->dropTable('student');
    }
}
