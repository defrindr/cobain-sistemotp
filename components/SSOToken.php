<?php
namespace app\components;

use app\models\User;
use Yii;

class SSOToken
{

    const prefix = "Bt19";
    const suffix = "s3cr37k3Y";
    const padding_1 = "_";
    const padding_2 = "+";
    const randomize_int = [30, 50];
    const Expired_In = 60*60*5;

    public static function generateToken()
    {
        $real_time = (string) (time() + static::Expired_In); // expired token in 5 hour after logged in
        $half_of_time = strlen($real_time) / 2;
        $real_times = [
            substr($real_time, 0, $half_of_time),
            substr($real_time, $half_of_time),
        ];

        $b64 = base64_encode($real_times[0] . static::padding_2 . Constant::generateRandomString(random_int(static::randomize_int[0], static::randomize_int[1])) . static::padding_2 . $real_times[1]);
        $half_of_b64 = strlen($b64) / 2;

        $padding = static::padding_1 . Constant::generateRandomString(5) . static::padding_1;

        $b64s = [
            substr($b64, 0, $half_of_b64),
            substr($b64, $half_of_b64),
        ];

        $b64 = implode($padding, $b64s);

        $generate_random_string = static::prefix . $b64 . static::suffix;
        return $generate_random_string;
    }

    public static function checkToken()
    {
        $flag = 0;
        $message = "Token tidak valid";

        $token = Yii::$app->request->headers->get('Authorization');
        $token = str_replace("Bearer ", "", $token);

        $user = User::findOne(['secret_token' => $token]);
        if ($user == null):
            goto end;
        endif;

        $b64s = str_replace(static::prefix, "", str_replace(static::suffix, "", $token));
        $b64s = explode(static::padding_1, $b64s);

        unset($b64s[1]); // remove padding

        $plaintoken = explode(static::padding_2, base64_decode(implode("", $b64s)));

        $time = (int) ($plaintoken[0] . end($plaintoken));
        $now = time();
        if ($time > $now):
            $flag = 1;
            $message = "Token Valid";
            Yii::$app->user->login($user); // logged in
        endif;
        
        end:
        return [
            "success" => ($flag == 1),
            "message" => $message,
        ];
    }
}
