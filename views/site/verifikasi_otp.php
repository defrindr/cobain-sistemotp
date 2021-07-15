<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = 'Verifikasi - ' . Yii::$app->name;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg text-center">Silakan masukkan OTP</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'otp', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('otp')]) ?>
        <hr>

        <div class="row">
            <div class="col-sm-5">
            </div>
            <!-- /.col -->
            <div class="col-sm-7 text-right">
                <?= Html::a("Kirim OTP", ["/site/kirim-otp"], [
                        "class" => "btn btn-primary",
                        "title" => "Hapus Data",
                        "data-confirm" => "Kirim otp ?",
                        //"data-method" => "GET"
                    ]);
                    ?>
                <?= Html::submitButton('<i class="fa fa-checked"></i> Verifikasi', ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->