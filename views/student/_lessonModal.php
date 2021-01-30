<?php
/**
 *  @var View $this
 *  @var array $attend
 */

use app\models\Student;
use yii\helpers\Html;
use yii\web\View;

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
                <h4 class="modal-title" id="hometask-<?= $attend['lessonid'] ?>">Занятие <?= date('d.m.Y', strtotime($attend['lessondate'])) ?></h4>
            </div>
		    <div class="modal-body">
                <p><b><?= Yii::t('app', 'Description') ?>:</b> <?= $attend['description'] ?></p>
                <p><b><?= Yii::t('app', 'Homework') ?>:</b> <?= $attend['homework'] ?></p>
                <p><b><?= Yii::t('app', 'Comments/Recommendations') ?>:</b> <?= $attend['comm'] ?></p>
                <p><b>Успешиков</b>: <?= $attend['successes'] ? join('', Student::prepareStudentSuccessesList((int)$attend['successes'])) : '-' ?></p>
            </div>
		</div>
	</div>
</div>