<?php
namespace app\assets;


use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $js = [
        // '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
        "//maps.googleapis.com/maps/api/js?key=AIzaSyCV6HOHjE9XM8IbEaL6ZMZdW8e0tavsOL8&libraries=places&region=id&language=en&sensor=false",
    ];
}