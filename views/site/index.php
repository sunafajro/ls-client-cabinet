<?php

/**
 * @var $this yii\web\View
 * @var $messages 
 * @var $comments
 */

use Yii;
use yii\helpers\Html;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="row">
        <div class="col-xs-12 col-sm-9">
        <?php if (!empty($messages)) { ?>
            <?php foreach ($messages as $message) { ?>
                <div class="panel panel-success">
                    <div class="panel-heading"><?= $message['data'] ?> :: <?= $message['name'] ?></div>
                    <div class="panel-body">
                        <?php if ($message['files']) { 
                            $files = $message['files'];
                            $addr = explode('|', $files);
                            if (isset($addr[0]) && $addr[0] != "") {
                                $addr = explode('|', $files);
                                $ext = explode('.', $addr[0]);
                                if ($ext[1] === 'jpg' || $ext[1] === 'png' || $ext[1] === 'bmp' || $ext[1] ==='gif' ) { ?>
                                    <?= Html::img(
                                        '@web/images/calc_message/' . $message['id'] . '/fls/' . $addr[0],
                                        [
                                            'class' => 'img-thumbnail',
                                            'style' => 'margin-right: 10px; float: left'
                                        ]) ?>
                                    <p style="text-align: justify">
                                        <?= $message['description'] ?>
                                    </p>
                                <?php } else { ?>
                                    <p style="text-align: justify">
                                        <?= $message['description'] ?>
                                    </p>
                                    <?= Html::a(
                                        count($addr) > 1 ? $addr[1] : '<none>',
                                        '@web/fls/calc_message/' . $message->id . '/fls/' . $addr[0]) ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <p style="text-align: justify">
                                <?= $message['description'] ?>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        </div>
        <div class="col-xs-12 col-sm-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <b>Последние комментарии</b>
                </div>
                <div class="panel-body" style="font-size: 12px">
                    <?php if (empty($comments)) { ?>
                        <p class="text-danger">У Вас пока нет комментариев к занятиям.</p>
                    <?php } else { ?>
                        <?php foreach ($comments as $comment) { ?>
                            <?php if ($comment['comments'] !== "") { ?>
                            <p>
                                <strong>Занятие <?= date('d.m.Y', strtotime($comment['date'])) ?></strong><br/>
                                <em><?= $comment['comments'] ?></em>
                            </p>
                            <?php } ?>
                        <?php } ?>
                        <div class="text-right">
                            <?= Html::a('Далее...', ['student/lessons']) ?>
                        </div>
                    <?php } ?>
                </div>
	        </div>
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b>Наши контакты</b>
                </div>
                <div class="panel-body" style="font-size: 12px">
                    <p>
                        <b>Чебоксары:</b>
                    </p>
                    <ul>
                        <li>Московский пр. 17, 6 этаж<br />Тел./факс (8352) 43-96-77</li>
                        <li>пр. Ленина 7, 3 этаж<br />Тел. (8352) 23-02-03</li>
                        <li>ул. Университетская 34, 2 этаж<br />Тел. (8352) 68-50-90</li>
                        <li>пр. 9 Пятилетки 19/37<br />Тел. (8352) 68-50-03</li>
                        <li>пр. М. Горького 12<br />Тел. (8352) 68-00-56</li>
                        <li>ул. Н. Смирнова 7<br />Тел. (8352) 68-03-45</li>
                    </ul>
                    <p>
                        <b>Новочебоксарск:</b>
                    </p>
                    <ul>
                        <li>ул. Пионерская 4/2<br />Тел. (8352) 68-00-52</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>