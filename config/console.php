<?php

$db = require(__DIR__ . '/db.php');

return [
    'id' => 'test-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => $db,
    ],
];
