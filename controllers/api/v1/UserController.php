<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "UserController".
 */

use app\components\Angka;
use app\components\Constant;
use app\components\Email;
use app\components\SSOToken;
use app\models\Profile;
use app\models\User;
use app\models\UserOtp;
use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class UserController extends \yii\rest\ActiveController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authentication'] = [
            'class' => \app\components\CustomAuth::class,
            'except' => ['login', 'register', 'kirim-otp', 'reset-password'],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function actionSecretMethodToCheckYourTokenIsValidOrNot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = SSOToken::checkToken();
        return $response;
    }

    public function actionThisIsReallyReallySecretMethodToGetDataForRegistrationAnotherModule()
    {
        $user = User::findOne(["id" => Yii::$app->user->id]);
        if ($user == null) {
            throw new HttpException(404);
        }

        $fields = $_POST['fields'];

        $data = [];
        if (is_array($fields)):
            foreach ($fields as $field):
                if ($user->hasAttribute($field)):
                    $data[$field] = $user->$field;
                endif;

            endforeach;
        else:
            if ($user->hasAttribute($fields)):
                $data[$fields] = $user->$fields;
            endif;
        endif;

        return [
            "success" => true,
            "message" => "successfully fetched data.",
            "data" => $data,
        ];
    }

    public function generateOtp($user)
    {
        if ($user == null) {
            return [
                "success" => false,
                "message" => "User tidak ditemukan",
            ];
        }

        $last_otp = UserOtp::find()->where(['user_id' => $user->id, 'is_used' => 0])->orderBy(['created_at' => SORT_DESC])->one();
        $time = time();

        if ($last_otp != null) {
            if ($time - 60 < strtotime($last_otp->created_at)) {
                $delay = (strtotime($last_otp->created_at) + 60) - $time;
                return [
                    "success" => false,
                    "message" => "Dapat mengirim ulang OTP dalam {$delay} detik",
                ];
            }
        }

        $not_used = UserOtp::find()->where(['user_id' => $user->id, 'is_used' => 0])->all();
        foreach ($not_used as $_nu) {
            $_nu->is_used = 1;
            $_nu->save();
        }

        $new_otp = new UserOtp();
        $new_otp->user_id = $user->id;
        $new_otp->otp_code = Angka::randomNumber(6);
        $new_otp->created_at = date("Y-m-d H:i:s");
        $new_otp->expired_at = date("Y-m-d H:i:s", $time + (60 * 5)); //expired 5 menit
        $new_otp->save();
        Email::send($user->username, "OTP CODE", "Kode OTP Anda " . $new_otp->otp_code . ". Hanya berlaku 5 menit");

        return [
            "success" => true,
            "message" => "OTP berhasil digenerate, Silahkan cek email anda.",
        ];
    }

    public function actionKonfirmasiUser()
    {
        if (Constant::isMethod(['POST']) == false) {
            throw new HttpException(405, "Method not allowed");
        }
        $user = Yii::$app->user->identity;
        if ($user->verifikasi) {
            return [
                "success" => true,
                "message" => "Akun telah aktif",
            ];
        }

        $now = time();
        $not_used = UserOtp::find()->activeOtp(Yii::$app->request->post("otp_code"), $user)->one();
        if ($not_used) {
            if (strtotime($not_used->expired_at) < $now) {
                return [
                    "success" => true,
                    "message" => "Kode OTP kadaluarsa",
                ];
            }

            $user->verifikasi = 1;
            $not_used->is_used = 1;
            $not_used->save();
            $user->save();

            return [
                "success" => true,
                "message" => "Akun berhasil diaktifkan",
            ];
        }

        return [
            "success" => false,
            "message" => "OTP salah",
        ];
    }

    public function actionKirimOtp()
    {
        if (Constant::isMethod(['POST']) == false) {
            throw new HttpException(405, "Method not allowed");
        }

        $user2 = User::findOne(["username" => Yii::$app->request->post('email')]);

        $user = Yii::$app->user->identity;
        return $this->generateOtp($user ?? $user2);
    }

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (strtolower(Yii::$app->request->method) != "post") {
            throw new HttpException(405);
        }

        $request = \yii::$app->request->post();
        $user = new User;
        $user->scenario = $user::SCENARIO_REGISTER_APP;
        $user->load($request, '');
        $user->no_hp = Constant::purifyPhone(Yii::$app->request->post('no_hp'));
        $user->username = Yii::$app->request->post('email');
        $user->role_id = 3;
        $user->verifikasi = 0;

        if ($user->validate()) {
            $user->password = md5($user->password);
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->save(false);
            $this->generateOtp($user);
            return ['success' => true, 'message' => 'success', 'token' => $user->secret_token];
        } else {
            throw new HttpException(400, Constant::flattenError($user->getErrors()));
        }
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (strtolower(Yii::$app->request->method) != "post") {
            throw new HttpException(405);
        }

        $flag = 0;
        $message = "Login gagal";
        $token = "";

        try {
            $user = User::findOne([
                "username" => $_POST['email'],
                "password" => md5($_POST['password']),
            ]);

            if (isset($user)):
                $generate_random_string = SSOToken::generateToken();
                $user->secret_token = $generate_random_string;
                $user->fcm_token = $_POST['fcm_token'];
                $user->last_login = date("Y-m-d H:i:s");
                $user->validate();
                $user->save(false);

                $flag = 1;
                $message = 'Login Berhasil';
                $token = $generate_random_string;
            endif;
        } catch (\Exception $e) {
            throw new HttpException(500);
        }

        return (object) [
            "success" => ($flag == 1),
            "message" => $message,
            "token" => $token,
        ];
    }

    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request->bodyParams;

        $user = User::findOne(["id" => Yii::$app->user->id]);
        $user->scenario = $user::SCENARIO_UPDATE;
        $old_photo = $user->photo_url;
        $user->load($request, '');

        if ($request['profile_id']) {
            $have_profile = Profile::find()->where(['id' => $request['profile_id'], 'user_id' => $user->id])->one();
            if ($have_profile == null) {
                throw new HttpException(422, "Profile tidak ditemukan");
            }
        }

        $photo = UploadedFile::getInstanceByName('photo_url');
        if ($photo) {
            $response = $this->uploadImage($photo, "user");
            if ($response->success == false) {
                throw new HttpException(422,"Gambar gagal diunggah");
            }
            $user->photo_url = $response->filename;
        } else {
            $user->photo_url = $old_photo;
        }

        $flag = 0;
        $message = "Profile gagal di update.";

        if ($user->validate()) {
            $user->save(false);
            $flag = 1;
            $message = "Profile berhasil di update.";
        }

        return [
            "success" => ($flag == 1),
            "message" => $message,
            "data" => $user,
        ];
    }

    public function actionView()
    {
        $message = "Data berhasil didapatkan.";
        $user = User::findOne(["id" => Yii::$app->user->id]);
        return [
            "success" => (1 == 1),
            "message" => $message,
            "data" => $user,
        ];
    }

    public function actionResetPassword()
    {
        if (Constant::isMethod('post') == false) {
            throw new HttpException(405);
        }
        $request = Yii::$app->request->post();
        $user = User::findOne(['username' => $request["email"]]);
        if ($user == null) {
            throw new HttpException(404, "User tidak dapat temukan");
        }
        $user->scenario=$user::SCENARIO_RESET_PASSWORD;

        $not_used = UserOtp::find()->activeOtp($request["otp_code"], $user)->withExpired()->one();
        if ($not_used == null) {
            throw new HttpException(404, "Token tidak valid");
        }

        $user->password = $request['new_password'];
        if ($user->validate()) {
            $user->password = md5($request['new_password']);
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->fcm_token = $_POST['fcm_token'];
            $user->last_login = date("Y-m-d H:i:s");
            $user->validate();
            $user->save();
            $not_used->is_used = 1;
            $not_used->save();
            $user->save(false);
            return [
                "success" => true,
                "message" => "Password berhasil diupdate"
            ];
        }
        throw new HttpException(400,Constant::flattenError($user->getErrors()));
    }
}
