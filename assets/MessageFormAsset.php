<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class MessageFormAsset extends AssetBundle
{
    public $basePath = '@webroot/messages';

    public $baseUrl = '@web/messages';

    public $css = [];

    public $js = [
        'js/messageForm.js'
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}