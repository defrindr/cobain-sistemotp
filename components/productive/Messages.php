<?php
namespace app\components\productive;

use Yii;
use yii\helpers\Url;

trait Messages {
    public function messageValidationError(){
        Yii::$app->session->setFlash("error", "Telah terjadi kesalahan");
    }

    public function messageValidationFailed($error = null){
        Yii::$app->session->setFlash("error", "Validasi gagal $error");
    }

    public function messageCreateSuccess($name = "Data"){
        Yii::$app->session->setFlash("success", "$name Berhasil di buat");
    }
    
    public function messageUpdateSuccess($name = "Data"){
        Yii::$app->session->setFlash("success", "$name Berhasil di ubah");
    }
    
    public function messageDeleteSuccess($name = "Data"){
        Yii::$app->session->setFlash("success", "$name Berhasil di hapus");
    }

    public function messageAccessForbidden($name = "Data"){
        Yii::$app->session->setFlash("error", "Fungsi ini belum bisa dijalankan, $name belum memenuhi standar");
    }

    public function messageDeleteForbidden($name = "Data") {
        Yii::$app->session->setFlash("error", "$name gagal di hapus karena sudah di konfirmasi.");
    }

    public function messageLetterNumberNotFound($name = "Penomoran"){
        Yii::$app->session->setFlash("error", "$name belum di atur, silahkan atur terlebih dahulu / hubungi administrator untuk mengatur penomoran pada surat ini.");
    }

    public function messageFailedSendWa($name = "Data"){
        Yii::$app->session->setFlash("error", "Gagal mengirim notifikasi Whatapps ketika $name di konfirmasi.");
    }
}
