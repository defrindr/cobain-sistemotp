<?php
namespace app\components;

use app\models\User;
use Yii;

class SSOHelper
{
    const IDENTITY_CLASS = "\app\models\Penduduk";
    const IDENTITY_VAR = "nik";
    const INTEGRATION_VARS = [
        "fcm_token" => "fcm_token",
    ];
    private static function request($url, $fields, $headers = [], $method = "GET")
    {

        $valid_header = [];

        foreach ($headers as $key => $val) {
            $valid_header[] = "$key: " . $val;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $valid_header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method == "POST"):
            curl_setopt($ch, CURLOPT_POST, true);
        endif;

        if (empty($fields) == false):
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        endif;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function get($url, $fields = null, $headers = [])
    {
        $response = json_decode(static::request($url, $fields, $headers, "GET"));

        return $response;
    }

    public static function post($url, $fields = [], $headers = [])
    {
        $response = json_decode(static::request($url, $fields, $headers, "POST"));

        return $response;
    }

    public static function getUserData($fields, $token)
    {
        $url = "http://localhost/kuota-batu/web/api/v1/user/this-is-really-really-secret-method-to-get-data-for-registration-another-module";
        $response = static::post($url, [
            "fields" => $fields,
        ], [
            "Authorization" => $token,
        ]);

        return $response;
    }

    public static function checkToken()
    {

        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace("Bearer ", "", $token);
        if ($token):
            $response = SSOHelper::getUserData(["username"], $token);
            if ($response->success):
                $data = (array) $response->data;
                if (isset($data['username'])):
                    $user = User::findOne(['username' => $data['username']]);
                    if ($user):
                        Yii::$app->user->login($user);
                        return $user;
                    endif;
                endif;
            endif;
        endif;

        return null;
    }
}
