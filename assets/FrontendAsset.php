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
class FrontendAsset extends AssetBundle
{
    public $basePath = '@webroot/frontend';
    public $baseUrl = '@web/frontend';

    public $css = [
        "https://fonts.googleapis.com/css?family=Open+Sans:300,400,600",
        "css/all.min.css",
        "css/bootstrap.min.css",
        "css/tooplate-style.css",
    ];

    public $js = [
        "js/jquery-1.9.1.min.js",
        "js/jquery.singlePageNav.min.js",
        "js/bootstrap.min.js",
        "js/main.js",
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapPluginAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
