<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class VerifikasiOtp extends Model
{
    public $otp;
    // public $captcha;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['otp'], 'required'],
            ['otp', 'number'],
            ['otp', 'validateOtp'],
        ];
    }

    public function attributeLabels()
    {
        return [
            "otp"=>"Kode OTP"
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOtp($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            $now = time();
            $not_used = UserOtp::find()->activeOtp($this->otp, $user)->one();
            if ($not_used) {
                if (strtotime($not_used->expired_at) < $now) {
                    $this->addError($attribute, "{$this->getAttributeLabel($attribute)} Kadaluarsa");
                    return false;
                }
                return true;
            }
            $this->addError($attribute, "{$this->getAttributeLabel($attribute)} tidak valid");
            return false;
        }
    }

    public function konfirmasi(){
        $user = Yii::$app->user->identity;
        $not_used = UserOtp::find()->activeOtp($this->otp, $user)->one();

        $user->verifikasi = 1;
        $not_used->is_used = 1;
        $not_used->save();
        $user->save();
    }
}
