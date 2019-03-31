<?php

/**
 * @var $this yii\web\View
 * @var $payments
 */

use Yii;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="panel panel-info">
        <div class="panel-heading">
            <b>Оплаты</b>
        </div>
        <table class="table table-stripped table-hover table-condensed">
            <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Date') ?></th>
                    <th><?= Yii::t('app', 'Sum') ?></th>
                    <th><?= Yii::t('app', 'Manager') ?></th>
                    <th><?= Yii::t('app', 'Office') ?></th>
                </tr>
            </thead>
            <?php if (!empty($payments)) { ?>
                <tbody>
                    <?php $num = 1; ?>
                    <?php $sum = 0; ?>
                    <?php foreach ($payments as $payment) { ?>
                        <tr>
                            <td>
                                <?= $num ?>
                            </td>
                            <td>
                                <?= $payment['date'] ?>
                            </td>
                            <td>
                                <?= number_format($payment['value'], 2, '.', ' ') ?>
                            </td>
                            <td>
                                <?= $payment['employee'] ?>
                            </td>
                            <td>
                                <?= $payment['office'] ?>
                            </td>
                        </tr>
                        <?php $num += 1; ?>
                        <?php $sum += $payment['value'] ?>
                    <?php } ?>
                    <tr>
                        <td class="text-right" colspan="5">
                            <b>Общая сумма оплат: <?= number_format($sum, 2, '.', ' ') ?></b>
                        </td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
</div>