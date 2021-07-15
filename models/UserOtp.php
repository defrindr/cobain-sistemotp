<?php

namespace app\models;

use Yii;
use \app\models\base\UserOtp as BaseUserOtp;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_otp".
 * Modified by Defri Indra M
 */
class UserOtp extends BaseUserOtp
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
        if(isset($parent['user_id'])) :
            unset($parent['user_id']);
            $parent['user_id'] = function($model) {
                return $model->user_id;
            };
        endif;
        if(isset($parent['otp_code'])) :
            unset($parent['otp_code']);
            $parent['otp_code'] = function($model) {
                return $model->otp_code;
            };
        endif;
        if(isset($parent['is_used'])) :
            unset($parent['is_used']);
            $parent['is_used'] = function($model) {
                return $model->is_used;
            };
        endif;
        if(isset($parent['created_at'])) :
            unset($parent['created_at']);
            $parent['created_at'] = function($model) {
                return $model->created_at;
            };
        endif;
        if(isset($parent['expired_at'])) :
            unset($parent['expired_at']);
            $parent['expired_at'] = function($model) {
                return $model->expired_at;
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
            'user_id',
            'otp_code',
            'is_used',
            'created_at',
            'expired_at',
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
