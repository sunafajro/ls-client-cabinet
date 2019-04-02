<?php

/**
 * @var $this yii\web\View
 * @var $lessons
 * @var $comments
 * @var $currentPage
 * @var $totalPages
 */

use Yii;
use yii\helpers\Html;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="col-sm-12 col-sm-9">
        <?php if (!empty($lessons)) { ?>
            <?php
                $ctrl = [];
                $i = 0;
            ?>
            <?php foreach ($lessons as $course) { ?>
                <?php if (!in_array($course['courseid'], $ctrl)) { ?>
                    <?php
                        $ctrl[$i] = $course['courseid'];
                        $i += 1;
                    ?>
                    <div class="panel panel-warning">		
                        <div class="panel-heading">
                            <b><?= $course['coursename'] ?></b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <b><?= Yii::t('app', 'Level') ?>:</b> <?= $course['level'] ?><br />
                                    <b><?= Yii::t('app', 'Teacher') ?>:</b> <?= $course['teacher'] ?><br />
                                    <b><?= Yii::t('app', 'Duration') ?>:</b> <?= $course['lessontime'] ?><br />
                                    <b><?= Yii::t('app', 'Office') ?>:</b> <?= $course['office'] ?>
                                </div>
                                <div class="col-sm-8">
                                    <?php $num = 1; ?>
                                    <?php foreach ($lessons as $attend) { ?>
                                        <?php if ($course['courseid'] == $attend['courseid']) { ?>
                                            <?= $this->render('_lessonModal', [
                                                'attend' => $attend
                                            ]) ?>
                                            <?php $num += 1; ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <b>
                                <?= Yii::t('app', 'Lesson count') ?>: <?= $num - 1 ?>
                            </b>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="col-sm-12 col-sm-3">
        <div class='panel panel-info'>
	        <div class="panel-heading">
                <b><?= Yii::t('app', 'Lesson comments') ?></b>
            </div>
            <div class="panel-body" style="font-size: 12px">
                <?php if (empty($comments)) { ?>
                    <p class="text-danger"><?= Yii::t('app', 'You have no comments to the classes.') ?></p>
                <?php } else {
                    foreach ($comments as $comment) { ?>
                        <p>
                            <b>
                                <?= Html::a(
                                    Yii::t('app', 'Lesson') . ' ' . date('d.m.Y', strtotime($comment['date'])),
                                    '#',
                                    [
                                        'data-toggle' => 'modal',
                                        'data-target' => '.hometask-' . $comment['id'],
                                    ])
                                ?>
                            </b><br/>
                            <em><?= $comment['comments'] ?></em>
                        </p>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xs-6 text-left">
                            <?php if ($currentPage > 1) { ?>
                            <?= Html::a(
                                '<i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>',
                                ['student/lessons', 'page' => $currentPage - 1],
                                ['class' => 'btn btn-primary btn-xs']
                            ) ?>
                            <?php } ?>
                        </div>
                        <div class="col-xs-6 text-right">
                            <?php if ($currentPage < $totalPages) { ?>
                            <?= Html::a(
                                '<i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>',
                                ['student/lessons', 'page' => $currentPage + 1],
                                [
                                    'class' => 'btn btn-primary btn-xs',
                                ]
                            ) ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>