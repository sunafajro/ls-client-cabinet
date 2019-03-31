<?php

/**
 * @var $this yii\web\View
 * @var $services
 */

use Yii;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="row">
        <div class="col-xs-12 col-sm-9">
        
        </div>
        <div class="col-xs-12 col-sm-3">
        
        </div>
    </div>
    <div class="row">
        <div class="panel panel-success">
            <div class="panel-heading">
                <b><?= Yii::t('app', 'Services') ?></b>
            </div>
            <table class="table table-bordered table-hover table-stripped table-condensed">
                <thead>
                    <tr>
                        <th>№</th>
                        <th><?= Yii::t('app', 'Language') ?></th>
                        <th><?= Yii::t('app', 'Learning type' ) ?></th>
                        <th><?= Yii::t('app', 'Lessons ordered') ?></th>
                        <th><?= Yii::t('app', 'Lessons passed') ?></th>
                        <th><?= Yii::t('app', 'Lessons rest') ?></th>
                    </tr>
                <tbody>
                    <?php $num = 1; ?>
                    <?php foreach ($services as $service) { ?>
                    <tr>
                        <td><?= $num ?></td>
                        <td>
                            <?= $service['languageName'] . ' (#' . $service['serviceid'] . ')' ?>
                        </td>
                        <td>
                            <?= $service['eduformName'] ?>
                        </td>
                        <td><?= $service['lessonpaied'] ?></td>
                        <td>
                            <?php $zerolesson = 0; ?>
                            <?php foreach($lessons as $lesson) { ?>
                                <?php if ($lesson['serviceid'] == $service['serviceid']) { ?>
                                    <?= $lesson['lessonattend'] ?>
                                    <?php $kk = $lesson['lessonattend']; ?>
                                    <?php $zerolesson += 1; ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($zerolesson == 0) { ?>
                                0
                                <?php $kk = 0; ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php $ostatok = $service['lessonpaied'] - $kk; ?>
                            <?php if ($ostatok < 0) { ?>
                                <span class='label label-danger'><?= abs($ostatok) ?></span>
                            <?php } else { ?>
                                <span class='label label-success'><?= $ostatok ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $num += 1; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>