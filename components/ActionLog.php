<?php
namespace app\components;

use app\models\ActionLog as ModelsActionLog;
use Yii;

class ActionLog {
    public static function record($controllers, $status, $msg){
        $user = Yii::$app->user->identity;
        $role = Yii::$app->user->identity->role;

        $controllers = explode("::", $controllers);

        $action_log = new ModelsActionLog();
        $action_log->user_id = $user->id;
        $action_log->role = $role->name;
        $action_log->username = $user->username;
        $action_log->controller = $controllers[0];
        $action_log->action = $controllers[1];
        $action_log->information = $msg;
        $action_log->status = $status;
        $action_log->timestamp = date('Y-m-d H:i:s', time());
        $action_log->save();
    }
}