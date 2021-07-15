<?php

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use \app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 */

$this->title = 'User ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud user-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List Users', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>
                                <?= $model->name ?> </h2>
                        </div>

                        <div class="panel-body">
                            <?php $this->beginBlock('app\models\User'); ?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'username',
                                    'name',
                                    [
                                        'format' => 'html',
                                        'attribute' => 'role_id',
                                        'value' => ($model->getRole()->one() ? Html::a($model->getRole()->one()->name, ['role/view', 'id' => $model->getRole()->one()->id,]) : '<span class="label label-warning">?</span>'),
                                    ],
                                    'photo_url:myimage',
                                    'last_login:iddate',
                                    'last_logout:iddate',
                                ],
                            ]); ?>

                            <hr />

                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger',
                                    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                                    'data-method' => 'post',
                                ]); ?>
                            <?php $this->endBlock(); ?>



                            <?= Tabs::widget(
                                [
                                    'id' => 'relation-tabs',
                                    'encodeLabels' => false,
                                    'items' => [[
                                        'label' => '<b class=""># ' . $model->id . '</b>',
                                        'content' => $this->blocks['app\models\User'],
                                        'active' => true,
                                    ],]
                                ]
                            );
                            ?>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
