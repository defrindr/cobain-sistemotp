<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'iCheck/all.css',
        'css/masterslider.css',
        'css/iconpicker.css',
        'css/jquery.dataTables.min.css',
    ];

    public $js = [
        'js/main.js',
        'iCheck/icheck.js',
        'js/masterslider.js',
        'js/jquery.easing.js',
        'js/rowsorter.js',
        'js/iconpicker.js',
        'js/jquery.dataTables.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
