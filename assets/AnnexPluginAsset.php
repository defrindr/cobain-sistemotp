<?php

namespace app\assets;


use yii\web\AssetBundle;

class AnnexPluginAsset extends AssetBundle
{
    public $basePath = '@webroot/admin/assets/plugins';
    public $baseUrl = '@web/admin/assets/plugins';
    public $css = [
        // 'sweet-alert2/sweetalert2.min.css'
    ];
    public $js = [
        '//cdn.jsdelivr.net/npm/sweetalert2@10',
    ];
}
