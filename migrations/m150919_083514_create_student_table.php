<?php

use yii\db\Migration;

class m150919_083514_create_student_table extends Migration
{
    public function up()
    {
        $this->createTable('student', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string(),
            'birthdate' => $this->date()->notNull(),
            'level' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('student');
    }
}
