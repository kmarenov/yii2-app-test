<?php

use yii\db\Migration;

class m150918_220056_create_teacher_table extends Migration
{
    public function up()
    {
        $this->createTable('teacher', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'gender' => $this->smallInteger()->notNull(),
            'phone' => $this->string()->unique(),
        ]);

        $this->createIndex('name', 'teacher', 'name');
        $this->createIndex('gender', 'teacher', 'gender');
    }

    public function down()
    {
        $this->dropIndex('name', 'teacher');
        $this->dropIndex('gender', 'teacher');
        $this->dropIndex('phone', 'teacher');

        $this->dropTable('teacher');
    }
}
