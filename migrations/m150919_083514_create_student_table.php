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
    }

    public function down()
    {
        $this->dropTable('student');
    }
}
