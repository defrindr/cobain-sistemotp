<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

echo "<?php\n";
?>

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
*/

$this->title = <?= $generator->generateString('Tambah Baru') ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= "<?= " ?>Html::a(<?= $generator->generateString('Kembali') ?>, \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
</p>

<?= "<?= " ?>$this->render('_form', [
'model' => $model,
]); ?>
