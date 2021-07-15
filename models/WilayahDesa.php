<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahDesa as BaseWilayahDesa;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_desa".
 * Modified by Defri Indra M
 */
class WilayahDesa extends BaseWilayahDesa
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    /**
     * @inheiritance
     */
    public function fields()
    {
        $parent = parent::fields();

        if(isset($parent['id'])) :
            unset($parent['id']);
            $parent['id'] = function($model) {
                return $model->id;
            };
        endif;
        if(isset($parent['kecamatan_id'])) :
            unset($parent['kecamatan_id']);
            $parent['kecamatan_id'] = function($model) {
                return $model->kecamatan_id;
            };
        endif;
        if(isset($parent['nama'])) :
            unset($parent['nama']);
            $parent['nama'] = function($model) {
                return $model->nama;
            };
        endif;
        return $parent;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    
    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'kecamatan_id',
            'nama',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        return $parent;
    }

    /**
     * Simplify return data xD
     */
    public function render() {
        return [
            "model" => $this
        ];
    }

    /**
     * override validate
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * override load
     */
    public function load($data, $formName = null, $service = "web")
    {
        return parent::load($data, $formName);
    }
}
