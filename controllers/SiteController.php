<?php

namespace app\controllers;

//use app\components\NodeLogger;

use app\components\Angka;
use app\components\Constant;
use app\components\Email;
use app\models\Action;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\MasterKota;
use app\models\RegisterForm;
use app\models\User;
use app\models\UserOtp;
use app\models\VerifikasiOtp;
use app\models\WilayahDesa;
use app\models\WilayahKecamatan;
use app\models\WilayahKota;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    use \app\components\UploadFile;

    public function behaviors()
    {
        return Action::getAccess($this->id);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => 'error',
                'layout' => false,
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionProfile()
    {
        $model = User::find()->where(["id" => Yii::$app->user->id])->one();
        $oldMd5Password = $model->password;
        $oldPhotoUrl = $model->photo_url;

        $model->password = "";

        if ($model->load($_POST)) {
            //password
            if ($model->password != "") {
                $model->password = md5($model->password);
            } else {
                $model->password = $oldMd5Password;
            }

            # get the uploaded file instance
            $image = UploadedFile::getInstance($model, 'photo_url');
            if (isset($image)) {
                $response = $this->uploadImage($image, "user");
                if ($response->success == false) {
                    Yii::$app->session->addFlash("error", "Error when upload image.");
                    $model->password = "";
                    return $this->render('profile', [
                        'model' => $model,
                    ]);
                }
                $model->photo_url = $response->filename;
                $this->deleteOne($oldPhotoUrl);
            } else {
                $model->photo_url = $oldPhotoUrl;
            }

            if ($model->validate()) {
                $this->deleteOne($oldPhotoUrl);
                $model->save();
                Yii::$app->session->addFlash("success", "Profile successfully updated.");
            } else {
                Yii::$app->session->addFlash("danger", "Profile cannot updated." . json_encode($model->getErrors()));
            }
            return $this->redirect(["profile"]);
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        $this->layout = "main-login";

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->addFlash("success", "Register success, please login");
            return $this->redirect(["site/login"]);
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    

    public function actionKirimOtp()
    {
        if (Constant::isMethod(['POST']) == false) {
            throw new MethodNotAllowedHttpException();
        }
        $user = Yii::$app->user->identity;
        
        if ($user == null) {
            throw new ForbiddenHttpException("User tidak ditemukan");
        }

        $last_otp = UserOtp::find()->where(['user_id' => $user->id, 'is_used' => 0])->orderBy(['created_at' => SORT_DESC])->one();
        $time = time();

        if ($last_otp != null) {
            if ($time - 60 < strtotime($last_otp->created_at)) {
                $delay = (strtotime($last_otp->created_at) + 60) - $time;
                Yii::$app->session->setFlash("error","Dapat mengirim ulang OTP dalam {$delay} detik");
                return $this->redirect(['/site/verifikasi']);
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
        Yii::$app->session->setFlash("success","Berhasil dikirim");
        return $this->redirect(['/site/verifikasi']);
    }
    

    public function actionVerifikasi()
    {
        $this->layout = "main-login";

        if (\Yii::$app->user->identity->verifikasi) {
            return $this->goHome();
        }

        $model = new VerifikasiOtp();
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->konfirmasi();
                Yii::$app->session->addFlash("success", "Akun berhasil di konfirmasi");
                return $this->redirect(["site/index"]);
            }
        }

        return $this->render('verifikasi_otp', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        $this->layout = "main-login";

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            //log last login column
            $user = Yii::$app->user->identity;
            $user->last_login = new Expression("NOW()");
            $user->save();

            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        //log last login column
        $user = Yii::$app->user->identity;
        $user->last_logout = new Expression("NOW()");
        $user->save();

        Yii::$app->user->logout();

        return $this->goHome();
    }

    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionGetKota() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys=array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];

            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getKota($cat_id);
                return ['output'=>$out, 'selected'=>$selected];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    public function actionGetKecamatan() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys=array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];

            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getKecamatan($cat_id);
                return ['output'=>$out, 'selected'=>$selected];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    public function actionGetDesa() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys=array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];

            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getDesa($cat_id);
                return ['output'=>$out, 'selected'=>$selected];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }

    protected static function getKota($provinsi_id){
        $model = WilayahKota::find()->where(['provinsi_id' => $provinsi_id])->all();
        $arr = [];
        foreach($model as $m): 
            $arr[] = [
                "id" => $m->id,
                "name" => $m->nama,
            ];
        endforeach;
        return $arr;
    }

    protected static function getKecamatan($kota_id){
        $model = WilayahKecamatan::find()->where(['kota_id' => $kota_id])->all();
        $arr = [];
        foreach($model as $m): 
            $arr[] = [
                "id" => $m->id,
                "name" => $m->nama,
            ];
        endforeach;
        return $arr;
    }

    protected static function getDesa($kecamatan_id){
        $model = WilayahDesa::find()->where(['kecamatan_id' => $kecamatan_id])->all();
        $arr = [];
        foreach($model as $m): 
            $arr[] = [
                "id" => $m->id,
                "name" => $m->nama,
            ];
        endforeach;
        return $arr;
    }

    public function actionAbout()
    {
        // $this->layout="../frontend-layouts/main";
        return $this->render('about');
    }
}
