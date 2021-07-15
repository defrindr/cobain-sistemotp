<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
*/

$this->title = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?> ' . $model-><?= $generator->getNameAttribute() ?> . ', ' . <?= $generator->generateString('Edit') ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Edit') ?>;
?>

<div class="row">
    <div class="col-md-12">
        <!-- <p>
            <?= "<?= " ?>Html::a(<?= $generator->generateString('Kembali') ?>, \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
        </p> -->
        <div class="card m-b-30">
            <div class="card-body">
                <?= "<?php " ?>echo $this->render('_form', $model->render()); ?>
            </div>
        </div>
    </div>
</div>
