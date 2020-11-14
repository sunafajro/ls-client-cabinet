<?php

$localPath = __DIR__ . '/local';

$aliases = require __DIR__ . '/aliases.php';

$db = require __DIR__ . '/db.php';
if (file_exists("{$localPath}/db.php")) {
    $localDb = require("{$localPath}/db.php");
    $db = array_merge($db, $localDb);
}

$params = require __DIR__ . '/params.php';
if (file_exists("{$localPath}/params.php")) {
    $localParams = require("{$localPath}/params.php");
    $params = array_merge($params, $localParams);
}

$options = require(__DIR__ . '/options.php');
if (file_exists("{$localPath}/options.php")) {
    $localOptions = require("{$localPath}/options.php");
    $options = array_merge($options, $localOptions);
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'components' => [
        'request' => [
            'enableCsrfValidation' => $options['enableCsrfValidation'],
            'cookieValidationKey'  => $options['cookieValidationKey'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Auth',
            'enableAutoLogin' => $options['enableAutoLogin'],
            'authTimeout'     => $options['authTimeout'],
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
                '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
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
    'aliases' => $aliases,
    'params' => $params,
];

if (YII_DEBUG === 'true') {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = ['*'];
}

if (YII_ENV === 'dev') {
    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
