<?php

$aliases = require __DIR__ . '/aliases.php';
$db      = require __DIR__ . '/db.php';
$params  = require __DIR__ . '/params.php';

if (file_exists(__DIR__ . '/local/db.php')) {
    require(__DIR__ . '/local/db.php');
    $db = array_merge($db, $localDb);
}

if (file_exists(__DIR__ . '/local/params.php')) {
    require(__DIR__ . '/local/params.php');
    $params = array_merge($params, $localParams);
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'aliases' => $aliases,
    'components' => [
        'request' => [
            'cookieValidationKey' => 'your secret key here',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Auth',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    	'i18n' => [
        	    'translations' => [
            		'app*' => [
            		    'class' => 'yii\i18n\PhpMessageSource',
                    	    'fileMap' => ['app' => 'app.php','app/error'=>'error.php'],
            		],
        	    ],
        ],
    ],
    'params' => $params,
];

if (file_exists(__DIR__ . '/local/request.php')) {
    require(__DIR__ . '/local/request.php');
    $config['components']['request'] = array_merge($config['components']['request'], $localRequest);
}

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
