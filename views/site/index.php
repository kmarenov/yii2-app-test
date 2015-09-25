<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>1. Все учителя</h2>

                <p>Список всех учителей с возможностью добавления новых</p>
                <p><?= Html::a('Перейти &raquo;', ['/teacher/index'], ['class' => 'btn btn-default']); ?></p>
            </div>
            <div class="col-lg-4">
                <h2>2. Все ученики</h2>

                <p>Список всех учеников с возможностью добавления новых</p>
                <p><?= Html::a('Перейти &raquo;', ['/student/index'], ['class' => 'btn btn-default']); ?></p>
            </div>
            <div class="col-lg-4">
                <h2>3. Список учителей, с которыми занимаются только ученики, родившиеся в апреле</h2>

                <p><?= Html::a('Перейти &raquo;', ['/teacher/april'], ['class' => 'btn btn-default']); ?></p>
            </div>
            <div class="col-lg-4">
                <h2>4. Имена любых двух учителей, у которых максимальное количество общих учеников, и список этих
                    общих учеников</h2>

                <p><?= Html::a('Перейти &raquo;', ['/student/teachers-with-max-common-students'],
                        ['class' => 'btn btn-default']); ?></p>
            </div>
        </div>

    </div>
</div>
