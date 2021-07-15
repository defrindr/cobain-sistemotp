<?php

use app\components\Constant;
use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\Menu $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
        'id' => 'Menu',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
);
?>
<div class="d-flex flex-wrap">
    <?= $form->field($model, 'name',Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'controller',Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'icon',Constant::COLUMN())->textInput(['class' => "form-control icp-auto", 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'parent_id',Constant::COLUMN())->dropDownList(
        \yii\helpers\ArrayHelper::map(app\models\Menu::find()->where(["parent_id"=>null])->orderBy("`order`")->all(), 'id', 'name'),
        ['prompt' => 'Select', 'options' => ['autocomplete' => 'off']]
    ); ?>
</div>
<hr/>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-md-offset-3 col-md-7">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs('$(".icp-auto").iconpicker();');
?>