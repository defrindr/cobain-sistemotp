<?php

/**
 * Defri Indra
 * 2021-04-16
 */

namespace app\components\annex;

use Yii;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
    public $fieldClass = 'app\components\annex\ActiveField';

    /**
     * {@inheritdoc}
     * @return ActiveField the created ActiveField object
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}
