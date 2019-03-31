<?php

/**
 * @var $this
 * @var $grades
 */
use Yii;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <b><?= Yii::t('app', 'Attestations') ?></b>
        </div>
        <table class="table table-bordered table-hover table-stripped table-condensed">
            <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Date') ?></th>
                    <th><?= Yii::t('app', 'Description') ?></th>
                    <th><?= Yii::t('app', 'Score') ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php if (!empty($grades)) { ?>
                        <?php $num = 1; ?>
                        <?php foreach ($grades as $grade) { ?>
                            <tr>
                                <td><?= $num ?></td>
                                <td><?= date('d.m.Y', strtotime($grade['date'])) ?></td>
                                <td><?= $grade['description'] ?></td>
                                <td><?= $grade['score'] ?><?= (int)$grade['type'] === 1 ? '%' : '' ?></td>
                            </tr>
                            <?php $num += 1; ?>
                        <?php } ?>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</div>