<?php

use app\components\Constant;
use app\models\Divisi;
use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2><?= $model->name ?> </h2>
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
                <?php $this->beginBlock('main'); ?>
                <div class="d-flex flex-wrap pt-3">
                    <?= $form->field($model, 'username', Constant::COLUMN(2, false))
                        ->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'password', Constant::COLUMN(2, false))
                        ->passwordInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'name', Constant::COLUMN(2, false))
                        ->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'role_id', Constant::COLUMN(2, false))
                        ->dropDownList(
                            \yii\helpers\ArrayHelper::map(app\models\Role::find()->all(), 'id', 'name'),
                            ['prompt' => 'Select']
                        ); ?>
                    <?= $form->field($model, 'photo_url', Constant::COLUMN(2, false))
                        ->widget(\kartik\file\FileInput::className(), [
                            'options' => ['accept' => 'image/*'],
                            'pluginOptions' => [
                                'allowedFileExtensions' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
                                'maxFileSize' => 250,
                            ],
                        ]) ?>
                    <?php
                    if ($model->photo_url != null) {
                    ?>
                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-3">
                                <?= Html::img(["uploads/" . $model->photo_url], ["width" => "150px"]); ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <?php $this->endBlock(); ?>

                <?=
                    Tabs::widget(
                        [
                            'encodeLabels' => false,
                            'items' => [[
                                'label'   => 'User',
                                'content' => $this->blocks['main'],
                                'active'  => true,
                            ],]
                        ]
                    );
                ?>
                <hr />
                <?php echo $form->errorSummary($model); ?>
                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-check"></span> ' .
                        ($model->isNewRecord ? 'Create' : 'Save'),
                    [
                        'id' => 'save-' . $model->formName(),
                        'class' => 'btn btn-success'
                    ]
                );
                ?>

                <?php ActiveForm::end(); ?>

            </div>

        </div>

    </div>

</div>