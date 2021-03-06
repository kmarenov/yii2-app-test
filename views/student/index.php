<?php

use app\models\Student;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($this->context->action->id == 'index'): ?>
    <p>
        <?= Html::a('Добавить ученика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php if (!empty($teachersNames)): ?>
        <h2>Учителя</h2>
        <ul>
            <li><h3><?= $teachersNames[0] ?></h3></li>
            <li><h3><?= $teachersNames[1] ?></h3></li>
        </ul>
        <h2>Ученики</h2>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
            'email:email',
            [
                'attribute' => 'birthdate',
                'value' => 'birthdate',
                'format' => 'html',
                'contentOptions' => ['style' => 'width:200px;']
            ],
            [
                'attribute' => 'level',
                'content' => function ($data) {
                    return $data->getLevelName();
                },
                'filter' => Student::getLevelsList()
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
