<?php

namespace app\models;

use app\components\Tanggal;
use Yii;

class User extends \app\models\base\User implements \yii\web\IdentityInterface
{

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
        if(isset($parent['username'])) :
            unset($parent['username']);
            $parent['username'] = function($model) {
                return $model->username;
            };
        endif;
        if(isset($parent['password'])) :
            unset($parent['password']);
            // $parent['password'] = function($model) {
            //     return $model->password;
            // };
        endif;
        if(isset($parent['name'])) :
            unset($parent['name']);
            $parent['name'] = function($model) {
                return $model->name;
            };
        endif;
        if(isset($parent['role_id'])) :
            unset($parent['role_id']);
            $parent['role_id'] = function($model) {
                return $model->role_id;
            };
        endif;
        if(isset($parent['provinsi_id'])) :
            unset($parent['provinsi_id']);
            $parent['provinsi_id'] = function($model) {
                return $model->provinsi_id;
            };
        endif;
        if(isset($parent['kota_id'])) :
            unset($parent['kota_id']);
            $parent['kota_id'] = function($model) {
                return $model->kota_id;
            };
        endif;
        if(isset($parent['kecamatan_id'])) :
            unset($parent['kecamatan_id']);
            $parent['kecamatan_id'] = function($model) {
                return $model->kecamatan_id;
            };
        endif;
        if(isset($parent['desa_id'])) :
            unset($parent['desa_id']);
            $parent['desa_id'] = function($model) {
                return $model->desa_id;
            };
        endif;
        if(isset($parent['alamat'])) :
            unset($parent['alamat']);
            $parent['alamat'] = function($model) {
                return $model->alamat;
            };
        endif;
        if(isset($parent['no_hp'])) :
            unset($parent['no_hp']);
            $parent['no_hp'] = function($model) {
                return $model->no_hp;
            };
        endif;
        if(isset($parent['profile_id'])) :
            unset($parent['profile_id']);
            $parent['profile_id'] = function($model) {
                return $model->profile_id;
            };
        endif;
        if(isset($parent['secret_token'])) :
            unset($parent['secret_token']);
            // $parent['secret_token'] = function($model) {
            //     return $model->secret_token;
            // };
        endif;
        if(isset($parent['fcm_token'])) :
            unset($parent['fcm_token']);
            // $parent['fcm_token'] = function($model) {
            //     return $model->fcm_token;
            // };
        endif;
        if(isset($parent['photo_url'])) :
            unset($parent['photo_url']);
            $parent['photo_url'] = function($model) {
                if(file_exists(Yii::getAlias("@webroot/uploads/$model->photo_url"))){
                    return Yii::getAlias("@file/$model->photo_url");
                }
                return Yii::getAlias("@link/default.png");
            };
        endif;
        if(isset($parent['verifikasi'])) :
            unset($parent['verifikasi']);
            $parent['verifikasi'] = function($model) {
                return $model->verifikasi;
            };
        endif;
        if(isset($parent['last_login'])) :
            unset($parent['last_login']);
            $parent['last_login'] = function($model) {
                return Tanggal::toReadableDate($model->last_login,false);
            };
        endif;
        if(isset($parent['last_logout'])) :
            unset($parent['last_logout']);
            $parent['last_logout'] = function($model) {
                return Tanggal::toReadableDate($model->last_logout,false);
            };
        endif;
        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::find()->where(["id" => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::find()->where(["username" => $username])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
