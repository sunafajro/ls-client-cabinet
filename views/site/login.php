<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm 
 * @var $model app\models\LoginForm
 */

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->params['siteTitle'];
$this->params['breadcrumbs'][] = Yii::t('app', 'Login');
$error = $model->hasErrors() ? $model->getErrors() : [];
$columnStyle = 'col-xs-12 col-sm-8 col-sm-offset-2';
?>
<div class="site-login">
    <div class="row" style="margin-bottom: 1rem">
        <div class="<?= $columnStyle ?> text-center" style="background-color: #e94f12">
            <?= Html::img('@web/images/yazyk_uspekha_logo_1.png', ['height' => '80px']) ?>
        </div>
    </div>
    <?php if (isset($error['date'])) { ?>
        <div class="row" style="margin-bottom: 1rem">
            <div class="<?= $columnStyle ?>">
                <?= Html::tag('div', $error['date'][0] ?? Yii::t('app', 'An error occurs.'), ['class' => 'alert alert-danger', 'style' => 'margin-bottom: 0px']) ?>
            </div>
        </div>
    <?php } ?>
    <div class="row" style="margin-bottom: 1rem">
        <div class="<?= $columnStyle ?>">
            <p style="margin-bottom: 0px"><?= Yii::t('app', 'Please fill out the following fields to login') ?>:</p>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
    ]); ?>
        <div class="row">
            <div class="<?= $columnStyle ?>">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="<?= $columnStyle ?>">
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="<?= $columnStyle ?> text-center">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
