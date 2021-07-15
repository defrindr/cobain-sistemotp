<?php
/**
 * Created by PhpStorm.
 * User: feb
 * Date: 30/05/16
 * Time: 00.14
 */

namespace app\components;

use yii\helpers\Html;

class ActionButton
{
    public static function getButtons($opts = [])
    {
        return [
            'class' => 'yii\grid\ActionColumn',
            "visible" => isset($opts['visible']) ? $opts['visible'] : true,
            'template' => ($opts['template']) ? $opts['template'] : '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-eye'></i>", ["view", "id" => $model->id], ["class" => "btn btn-success", "title" => "Lihat Data"]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-pencil'></i>", ["update", "id" => $model->id], ["class" => "btn btn-warning", "title" => "Edit Data"]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-trash'></i>", ["delete", "id" => $model->id], [
                        "class" => "btn btn-danger",
                        "title" => "Hapus Data",
                        "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                        //"data-method" => "GET"
                    ]);
                },
            ],
            'contentOptions' => ['nowrap' => 'nowrap', 'style' => 'text-align:center;width:160px'],
        ];
    }
}
