<?php

/**
 * @var $this yii\web\View
 * @var $messages
 * @var $messageForm
 * @var $receivers
 */

use Yii;
use yii\helpers\Html;
use app\widgets\Alert;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->params['siteTitle'];
?>
<div class="content-block">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= Alert::widget() ?>
            <?= ListView::widget([
                'dataProvider' => $messages,
                'itemView' => '_messagesList',
                'layout' => "{items}\n{pager}",
                'pager' => [
                    'maxButtonCount' => 5,
                ],
            ]) ?>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b><?= Yii::t('app', 'Send message')?></b>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'send-message-form',
                    ]); ?>
                    <?= $form->field($messageForm, 'refinement_id')->dropDownList($receivers) ?>
                    <?= $form->field($messageForm, 'name')->textInput() ?>
                    <?= $form->field($messageForm, 'description')->textArea(['rows' => 5]) ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-default']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>