<?php

/**
 * @var $this yii\widgets\ListView
 * @var $model
 */

use Yii;
?>
<div class="panel panel-<?= (int)$model['type'] === 13 ? 'success' : 'info' ?>">
    <div class="panel-heading">
        <?= date('d.m.Y', strtotime($model['date'])) . ' :: ' . $model['title'] . ' :: ' . ((int)$model['type'] === 13 ? '' : $model['receiver']) ?>
    </div>
    <div class="panel-body">
        <div class="text-justify"><?= $model['text'] ?></div>
        <div class="text-right">
            <small><?= ((int)$model['type'] === 13 ? $model['sender'] : Yii::$app->user->identity->name) ?></small>
        </div>
    </div>
</div>