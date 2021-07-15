<?php
namespace app\components;

use Yii;

class Email {
    public static function send($send_to, $subject, $content){
        return Yii::$app->mailer->compose()
            ->setTo($send_to)
            ->setFrom(Yii::$app->params['adminEmail.email'])
            ->setSubject($subject)
            ->setHtmlBody($content)
            ->send();
    }
}