<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\UserOtp]].
 *
 * @see \app\models\UserOtp
 */
class UserOtpQuery extends \yii\db\ActiveQuery
{
    public function activeOtp($otp_code, $user)
    {
        $this->andWhere(['user_id' => $user->id, 'otp_code' => $otp_code, 'is_used' => 0])->orderBy(['expired_at' => SORT_DESC]);
        return $this;
    }

    public function withExpired()
    {
        $now = date("Y-m-d H:i:s");
        $this->andWhere(['>', 'expired_at', $now]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\UserOtp[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\UserOtp|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
