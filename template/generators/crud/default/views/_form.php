<?php

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$model->setScenario('crud');
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->getTableSchema()->columnNames;
}

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
* @var yii\widgets\ActiveForm $form
*/

?>

<?= "<?php " ?>$form = ActiveForm::begin([
    'id' => '<?= $model->formName() ?>',
    'layout' => '<?= $generator->formLayout ?>',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?= "<?php " ?>echo $form->errorSummary($model); ?>
<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
<?php
foreach ($safeAttributes as $attribute) {
    $prepend = $generator->prependActiveField($attribute, $model);
    $field   = $generator->activeField($attribute, $model);
    $append  = $generator->appendActiveField($attribute, $model);
    $skipped = [
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "id",
        "flag",
    ];
    if(in_array($attribute, $skipped)) continue;
    $field = str_replace("field(\$model, '$attribute')->", "field(\$model, '$attribute', \app\components\Constant::COLUMN())->", $field);
    if ($prepend) {
        echo "\n\t\t\t" . $prepend;
    }
    if ($field) {
        echo "\n\t\t\t<?= " . $field . " ?>";
    }
    if ($append) {
        echo "\n\t\t\t" . $append;
    }
}
?>
<?= "\n" ?>
<div class="clearfix"></div>
</div>
<hr/>
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= "<?= " ?> Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= "<?= " ?> Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?= "<?php " ?>ActiveForm::end(); ?>