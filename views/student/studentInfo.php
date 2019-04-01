<?php

/**
 * @var $this yii\web\View
 * @var $balance
 * @var $student
 */

use Yii;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <b><?= Yii::t('app', 'Information') ?></b>
    </div>
    <div class="panel-body">
        <p style="font-size: 16px">
            <?= Html::tag(
                'i',
                '',
                [
                    'class' => 'glyphicon glyphicon-user',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'aria-hidden' => 'true',
                    'title' => Yii::t('app', 'Full name')
                ]
            ) ?> <?= $student->name ?>
        </p>
        <?php if ($student->phone) { ?>
            <p style="font-size: 16px">
                <?= Html::tag(
                    'i',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-phone-alt',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'aria-hidden' => 'true',
                        'title' => Yii::t('app', 'Phone')
                    ]
                ) ?> <?= $student->phone ?>
            </p>
        <?php } ?>
        <?php if ($student->email) { ?>
            <p style="font-size: 16px">
                <?= Html::tag(
                    'i',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-envelope',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'aria-hidden' => 'true',
                        'title' => Yii::t('app', 'E-mail')
                    ]
                ) ?> <?= $student->email ?>
            </p>
        <?php } ?>
        <p style="font-size: 16px">
            <?= Html::tag(
                    'i',
                    '',
                    [
                        'class' => 'glyphicon glyphicon-ruble',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top',
                        'aria-hidden' => 'true',
                        'title' => Yii::t('app', 'Balance')
                    ]
                ) ?> <?= $balance >= 0 ? Html::tag('b', $balance, ['class' => 'text-success']) : Html::tag('b', $balance, ['class' => 'text-danger']) ?>
            </p>
    </div>
</div>