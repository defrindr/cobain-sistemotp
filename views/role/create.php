<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\Role $model
*/

$this->title = 'Tambah';
$this->params['breadcrumbs'][] = ['label' => 'Hak Akses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card m-b-30">
    <div class="card-body">
        <?php echo $this->render('_form', [
            'model' => $model,
        ]); ?>
    </div>
</div>