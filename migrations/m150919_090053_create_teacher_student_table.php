<?php

use yii\db\Migration;

class m150919_090053_create_teacher_student_table extends Migration
{
    public function up()
    {
        $this->createTable('teacher_student', [
            'teacher_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('teacher_student');
    }
}
