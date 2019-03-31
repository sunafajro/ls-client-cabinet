<?php
/**
 *  @var $this yii\web\View
 *  @var $attend
 */

use yii\helpers\Html;
?>
<?php
    $className = '';
    switch ($attend['studstatusid']) {
    case 1:
        $className = 'success';
        break;
    case 2:
        $className = 'warning';
        break;
    case 3:
        $className = 'danger';
        break;
    default:
        $className = 'default';
    }
    echo Html::button(
        date('d.m.Y', strtotime($attend['lessondate'])),
        [
            'class' => 'btn btn-' . $className,
            'data-toggle' => 'modal',
            'data-target' => '.hometask-' . $attend['lessonid'],
            'style' => 'margin: 0 0 0.2rem 0',
        ]
    );
?>
<div class="modal fade bs-example-modal-lg hometask-<?= $attend['lessonid'] ?>" tabindex="-1" role="dialog" aria-labelledby="hometask-<?= $attend['lessonid'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
	    <div class="modal-content">
		    <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="hometask-<?= $attend['lessonid'] ?>"><?= $attend['lessondate'] ?></h4>
            </div>
		    <div class="modal-body">
                <p><b><?= Yii::t('app', 'Description') ?>:</b> <?= $attend['description'] ?></p>
                <p><b><?= Yii::t('app', 'Homework') ?>:</b> <?= $attend['homework'] ?></p>
                <p><b><?= Yii::t('app', 'Comments/Recomendations') ?>:</b> <?= $attend['comm'] ?></p>
            </div>
		</div>
	</div>
</div>