<?php

use app\components\Constant;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">


                    <div class="panel panel-default">
                        <div class="panel-heading p-2" style="border-radius:10px;margin-bottom:10px">
                            <h2><?=$model->name?>
                        </div>

                        <div class="panel-body">

                            <div class="user-form">

                                <?php $form = ActiveForm::begin([
                                    'id' => 'User',
                                    'layout' => 'horizontal',
                                    'enableClientValidation' => false,
                                    'errorSummaryCssClass' => 'error-summary alert alert-error',
                                    'options' => ['enctype' => 'multipart/form-data'],
                                ]);
                                ?>

                                <div class="">
                                    <?php $this->beginBlock('main');?>

                                    <div class="d-flex flex-wrap mt-3">
                                        <input type="tel" hidden /> <!-- disable chrome autofill -->
                                        <?=$form->field($model, 'password', Constant::COLUMN())->passwordInput(['maxlength' => true, 'autocomplete' => "off"])?>
                                        <?=$form->field($model, 'name', Constant::COLUMN())->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'photo_url', Constant::COLUMN())->widget(\kartik\file\FileInput::className(), [
                                            'options' => ['accept' => 'image/*'],
                                            'pluginOptions' => [
                                                'allowedFileExtensions' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
                                                'maxFileSize' => 250,
                                            ],
                                        ])?>
                                        <?php
                                        if ($model->photo_url != null) {
                                            ?>
                                        <div class="form-group">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <?=Html::img([$model->photo_url], ["width" => "150px"]);?>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php $this->endBlock();?>

                                    <?= Tabs::widget(
                                            [
                                                'encodeLabels' => false,
                                                'items' => [[
                                                    'label' => 'User',
                                                    'content' => $this->blocks['main'],
                                                    'active' => true,
                                                ]],
                                            ]
                                        );
                                        ?>
                                    <hr />
                                    <?php echo $form->errorSummary($model); ?>
                                    <?=Html::submitButton(
                                        '<span class="glyphicon glyphicon-check"></span> ' .
                                        ($model->isNewRecord ? 'Create' : 'Save'),
                                        [
                                            'id' => 'save-' . $model->formName(),
                                            'class' => 'btn btn-success',
                                        ]
                                    );
                                    ?>

                                    <?php ActiveForm::end();?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>