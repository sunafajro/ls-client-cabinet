<?php

defined('YII_DEBUG') or define('YII_DEBUG', isset($_SERVER['YII_DEBUG']) ? filter_var($_SERVER['YII_DEBUG'], FILTER_VALIDATE_BOOLEAN) : true);
defined('YII_ENV') or define('YII_ENV', isset($_SERVER['YII_ENV']) ? $_SERVER['YII_ENV'] : 'dev');

if (YII_ENV === 'maintenance') {
    echo "В данный момент сайт находится в режиме обслуживания. Попробуйте зайти еще раз позже.";
} else {
    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

    $config = require __DIR__ . '/../config/web.php';
    (new yii\web\Application($config))->run();
}