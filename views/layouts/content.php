<?php
use app\components\annex\Breadcrumbs;
use app\components\annex\Alert;

?>


<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group float-right">
                        <?=  Breadcrumbs::widget(
                    [
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]
                ) ?>
                    </div>
                    <h2 class="page-title">
                        <?php
                    if ($this->title !== null) {
                        echo \yii\helpers\Html::encode($this->title);
                    } else {
                        echo \yii\helpers\Inflector::camel2words(
                            \yii\helpers\Inflector::id2camel($this->context->module->id)
                        );
                        echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                    } ?>
                    </h2>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="content-wrapper">
            <section class="content">
            
                <?= Alert::widget() ?>
                <?= $content ?>
            </section>
        </div>
    </div><!-- container -->


</div> <!-- Page content Wrapper -->

<footer class="footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Defri</a>.</strong> All rights
    reserved.
</footer>