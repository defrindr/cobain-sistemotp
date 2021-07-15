<?php

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use \app\components\annex\Tabs;

/**
* @var yii\web\View $this
* @var app\models\Menu $model
*/

$this->title = 'Menu ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud menu-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id],['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List Menus', ['index'], ['class'=>'btn btn-default']) ?>
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <?= $model->name ?>            </h2>
        </div>

        <div class="panel-body">



    <?php $this->beginBlock('app\models\Menu'); ?>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
            'id',
        'name',
        'controller',
        'action',
        'icon',
        'order',
// generated by schmunk42\giiant\generators\crud\providers\RelationProvider::attributeFormat
[
    'format' => 'html',
    'attribute' => 'parent_id',
    'value' => ($model->getParent()->one() ? Html::a($model->getParent()->one()->name, ['menu/view', 'id' => $model->getParent()->one()->id,]) : '<span class="label label-warning">?</span>'),
],
    ],
    ]); ?>

    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


    
<?php $this->beginBlock('Menuses'); ?>
<div style='position: relative'><div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Menuses',
            ['menu/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Menus',
            ['menu/create', 'Menus' => ['parent_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div></div><?php Pjax::begin(['id'=>'pjax-Menuses', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Menuses ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>
<?= '<div class="table-responsive">' . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getMenus(), 'pagination' => ['pageSize' => 20, 'pageParam'=>'page-menuses']]),
    'pager'        => [
        'class'          => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel'  => 'Last'
    ],
    'columns' => [[
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = 'menu' . '/' . $action;
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'menu'
],
        'id',
        'name',
        'controller',
        'action',
        'icon',
        'order',
]
]) . '</div>' ?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('RoleMenuses'); ?>
<div style='position: relative'><div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Role Menuses',
            ['role-menu/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Role Menus',
            ['role-menu/create', 'RoleMenus' => ['menu_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div></div><?php Pjax::begin(['id'=>'pjax-RoleMenuses', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-RoleMenuses ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>
<?= '<div class="table-responsive">' . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getRoleMenus(), 'pagination' => ['pageSize' => 20, 'pageParam'=>'page-rolemenuses']]),
    'pager'        => [
        'class'          => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel'  => 'Last'
    ],
    'columns' => [[
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = 'role-menu' . '/' . $action;
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'role-menu'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'role_id',
    'value' => function ($model) {
        if ($rel = $model->getRole()->one()) {
            return Html::a($rel->name, ['role/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
]
]) . '</div>' ?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?= Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [ [
    'label'   => '<b class=""># '.$model->id.'</b>',
    'content' => $this->blocks['app\models\Menu'],
    'active'  => true,
],[
    'content' => $this->blocks['Menuses'],
    'label'   => '<small>Menuses <span class="badge badge-default">'.count($model->getMenus()->asArray()->all()).'</span></small>',
    'active'  => false,
],[
    'content' => $this->blocks['RoleMenuses'],
    'label'   => '<small>Role Menuses <span class="badge badge-default">'.count($model->getRoleMenus()->asArray()->all()).'</span></small>',
    'active'  => false,
], ]
                 ]
    );
    ?>
        </div>
    </div>
</div>
