<?php
$config = [
    'id' => 'test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sZoD9j6LYn_e3lrfjE3QbVqvAJ7Ghyks',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'language' => 'ru-RU',
    'defaultRoute' => 'teacher/index',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        //'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['*'],
    ];
}

return $config;
