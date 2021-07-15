<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = 'Register';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>",
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>",
];

$fieldOptions3 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>",
];
?>


    <div class="container-fluid">
        <div class="row" style="background: #fff">
            <div class="col-md-12 col-sm-12 text-center">
                <div>
                    <h3><?=Html::a("Login", ["site/login"])?> | Daftar</h3>
                    <?php $form = ActiveForm::begin(['id' => 'register-form', 'enableClientValidation' => false]);?>
                    <?=$form
                        ->field($model, 'name', $fieldOptions1)
                        ->label(false)
                        ->textInput(['placeholder' => $model->getAttributeLabel('name')])?>
                    <?=$form
                        ->field($model, 'username', $fieldOptions2)
                        ->label(false)
                        ->textInput(['placeholder' => $model->getAttributeLabel('username')])?>
                    <?=$form
                        ->field($model, 'password', $fieldOptions3)
                        ->label(false)
                        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])?>
                    <div class="row  text-right">
                        <div class="col-sm-12">
                            <?=Html::submitButton("<i class='fa fa-lock'></i> DAFTAR", ['class' => 'btn btn-primary btn-flat', 'name' => 'register-button'])?>
                        </div>
                    </div>
                    <?php ActiveForm::end();?>
                </div>
            </div>
        </div>
    </div>