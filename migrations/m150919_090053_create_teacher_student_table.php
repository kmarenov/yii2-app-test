<?php

use yii\db\Migration;

class m150919_090053_create_teacher_student_table extends Migration
{
    public function up()
    {
        $this->createTable('teacher_student', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer()->notNull(),
            'student_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('teacher_id', 'teacher_student', 'teacher_id');
        $this->createIndex('student_id', 'teacher_student', 'student_id');
        $this->createIndex('teacher_id_student_id', 'teacher_student', ['teacher_id', 'student_id']);
    }

    public function down()
    {
        $this->dropIndex('teacher_id', 'teacher_student');
        $this->dropIndex('student_id', 'teacher_student');

        $this->dropTable('teacher_student');
    }
}
