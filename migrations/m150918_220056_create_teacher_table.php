<?php

use yii\db\Migration;

class m150918_220056_create_teacher_table extends Migration
{
    public function up()
    {
        $this->createTable('teacher', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'gender' => $this->integer()->notNull(),
            'phone' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('teacher');
    }
}
