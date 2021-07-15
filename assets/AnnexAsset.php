<?php

namespace app\assets;

use yii\web\AssetBundle;

class AnnexAsset extends AssetBundle
{
    public $basePath = '@webroot/admin';
    public $baseUrl = '@web/admin';

    public $css = [
        "assets/css/bootstrap.min.css",
        "assets/css/icons.css",
        "assets/css/style.css",
        "assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css",

        '../css/iconpicker.css',
        '../css/site.css',
        '../css/select2.min.css',
    ];
    public $js = [
        // "assets/js/jquery.min.js",
        "assets/js/popper.min.js",
        "assets/js/bootstrap.min.js",
        "assets/js/modernizr.min.js",
        "assets/js/detect.js",
        "assets/js/fastclick.js",
        "assets/js/jquery.slimscroll.js",
        "assets/js/jquery.blockUI.js",
        "assets/js/waves.js",
        "assets/js/jquery.nicescroll.js",
        "assets/js/jquery.scrollTo.min.js",
        "assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",

        "assets/js/app.js",
        '../js/iconpicker.js',
        '../js/rowsorter.js',
        '../js/select2/select2.min.js',
    ];

    public $depends = [
        '\app\assets\AdminLtePluginAsset',
        '\app\assets\AnnexPluginAsset',
    ];
}
