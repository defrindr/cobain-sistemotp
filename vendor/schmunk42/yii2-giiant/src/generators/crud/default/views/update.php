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

<p>
    <?= "<?= " ?>Html::a('<i class="fa fa-eye-open"></i> Lihat', ['view', <?= $urlParams ?>], ['class' => 'btn btn-default']) ?>
</p>

<?= "<?php " ?>echo $this->render('_form', [
'model' => $model,
]); ?>
