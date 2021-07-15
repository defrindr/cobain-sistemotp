<?php
namespace app\components;

use app\models\FcmToken;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

// DIMATIKAN
class FcmTokenHelper {
    
    public static function actionSendMessage()
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if ($request->isPost) {

            $url = 'https://fcm.googleapis.com/fcm/send';

            $msg = array
                (
                'body' => $_POST['message'],
                'title' => $_POST['message'],
                'vibrate' => 1,
                'sound' => "default",
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );

            $registration_ids = [];

            $id = $_POST['id'];
            if (isset($_POST['list_fcm'])) {
                $registration_ids = json_decode($_POST['list_fcm']);
            } else if ($id) {
                $model = User::findOne(["id" => $id]);
                if ($model) {
                    $fcm_tokens = [];//FcmToken::find()->where(['user_id' => $id])->select(['id', 'fcm_token'])->all();
                    if ($fcm_tokens != []) {
                        $fcm_tokens = array_values(ArrayHelper::map($fcm_tokens, 'id', 'fcm_token'));
                    }
                    $registration_ids = $fcm_tokens;
                }
            }

            if ($registration_ids != []) {

                $fields = array
                    (
                    'registration_ids' => $registration_ids,
                    'priority' => "high",
                    'notification' => $msg,
                    'data' => [
                        'message' => $_POST['message'],
                        "navigation" => isset($_POST['navigation']) ? $_POST['navigation'] : null,
                        "id" => isset($_POST['id']) ? $_POST['id'] : null,
                    ],
                );

                $headers = array(
                    'Authorization:key = AAAAgnLEhBo:APA91bFV46fSnwGfMORYv-KD8edJIfbhc-tjZThxcKa5ZtnBvSotvKWhnumyh-3F4hk1fd4gfhxLePLabjObpGZIYry08zZJtXlyn3lwnQ1d8k8r9vp1JUWgAxwurUCJnwT6riJSBYOu ',
                    'Content-Type: application/json',
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                if ($result === false) {
                    die('Curl failed: ' . curl_error($ch));
                }
                curl_close($ch);
                return $result;
            }

        }

    }
}