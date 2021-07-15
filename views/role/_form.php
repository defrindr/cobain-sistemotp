<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\Role $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
        'id' => 'Role',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
);
?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<hr/>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-md-offset-3 col-md-7">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
