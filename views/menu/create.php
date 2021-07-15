<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\Menu $model
*/

$this->title = 'Tambah';
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-sm-12">
        <div class="card m-b-30">
            <div class="card-body">
                <?php echo $this->render('_form', [
                    'model' => $model,
                ]); ?>
            </div>
        </div>
    </div>
</div>