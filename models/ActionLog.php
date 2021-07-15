<?php

namespace app\models;

use Yii;
use \app\models\base\ActionLog as BaseActionLog;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "action_log".
 */
class ActionLog extends BaseActionLog
{

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
}
