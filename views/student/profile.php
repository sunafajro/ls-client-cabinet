<?php

/**
 * @var $this yii\web\View
 * @var $balance
 * @var $lessons
 * @var $services
 * @var $student
 */

use Yii;
use yii\helpers\Html;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <?= $this->render('studentInfo.php', [
                'student' => $student,
                'balance' => $balance
            ]) ?>
	    </div>
        <div class="col-xs-12 col-sm-3">
        
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
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
                                <?= Html::tag(
                                    'i',
                                    '',
                                    [
                                        'class' => 'glyphicon glyphicon-info-sign',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'aria-hidden' => 'true',
                                        'title' => '#' . $service['serviceId'] . ' ' . $service['serviceName']
                                    ]
                                ) ?>
                                <?= Html::tag(
                                    'span',
                                    (int)$service['languageId'] !== 16 ? $service['languageName'] : $service['serviceName']
                                ) ?>
                            </td>
                            <td>
                                <?= $service['eduformName'] ?>
                            </td>
                            <td><?= $service['lessonPaied'] ?></td>
                            <td>
                                <?php $zerolesson = 0; ?>
                                <?php foreach($lessons as $lesson) { ?>
                                    <?php if ((int)$lesson['serviceId'] === (int)$service['serviceId']) { ?>
                                        <?= $lesson['lessonAttend'] ?>
                                        <?php $kk = $lesson['lessonAttend']; ?>
                                        <?php $zerolesson += 1; ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($zerolesson == 0) { ?>
                                    0
                                    <?php $kk = 0; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php $ostatok = $service['lessonPaied'] - $kk; ?>
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
</div>
<?php
$js = <<< JS
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
JS;
$this->registerJs($js);
?>