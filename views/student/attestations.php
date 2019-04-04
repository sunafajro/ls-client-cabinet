<?php

/**
 * @var yii\web\View $this
 * @var array $grades
 */
use Yii;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <b><?=Yii::t('app', 'Attestations')?></b>
        </div>
        <?= GridView::widget([
            'dataProvider' => $grades,
            'layout'=>"{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => '№',
                    'headerOptions' => ['width' => '5%'],
                ],
                [
                    'attribute' => 'date',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '10%'],
                    'label' => Yii::t('app', 'Date'),
                    'value' => function ($grade) {
                        return date('d.m.Y', strtotime($grade['date']));
                    }
                ],
                [
                    'attribute' => 'description',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Description'),
                    'value' => function ($grade) {
                        return $grade['description'];
                    }
                ],
                [
                    'attribute' => 'score',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '10%'],
                    'label' => Yii::t('app', 'Score'),
                    'value' => function ($grade) {
                        return $grade['score'] . ((int)$grade['type'] === 1 ? '%' : '');
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Act.'),
                    'headerOptions' => ['width' => '5%'],
                    'template' => '{pdf}',
                    'buttons' => [
                        'pdf' => function ($url, $grade) {
                            return Html::a(
                                Html::tag('i',
                                '',
                                [
                                    'class' => 'glyphicon glyphicon-print',
                                    'aria-hidden' => true
                                ]),
                                ['student/download-attestation', 'id' => $grade['id']],
                                [
                                    'class' => 'btn btn-default btn-xs',
                                    'target' => '_blank'
                                ]
                            );
                        }
                    ],
                ],
            ],
        ])?>
    </div>
</div>