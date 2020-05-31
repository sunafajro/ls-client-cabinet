<?php

/**
 * @var View $this
 * @var File $model
 */

use app\models\File;
use yii\helpers\Html;
use yii\web\View;
?>
<div class="<?= is_null($file) ? 'hidden js--file-block-template' : '' ?>" style="display: inline-block; margin-right: 5px">
        <span class="label label-info">
            <span class="js--file-name" style="cursor: pointer"><?= $file->original_name ?? null ?></span>
            <i class="fa fa-times js--remove-file"></i>
        </span>
    <input type="hidden" name="<?= Html::getInputName($model, 'files[]') ?>" value="<?= $file->id ?? null ?>" />
</div>