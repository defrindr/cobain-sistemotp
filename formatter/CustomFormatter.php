<?php
namespace app\formatter;

use app\components\Angka;
use app\components\Tanggal;
use Yii;
use yii\i18n\Formatter;

class CustomFormatter extends Formatter
{
    public function asMyimage($link)
    {
        $link = Yii::getAlias("@file/$link");
        return "<a  href='$link' target='_blank'><img src='$link' class='img img-response' style='width: 80px;'></a>";
    }

    public function asIdtime($time)
    {
        return Tanggal::getTimeReadable($time, true);
    }
    
    public function asDownload($link)
    {
        $link = Yii::getAlias("@file/$link");
        return "<a href='$link' class='btn btn-primary' target='_blank'>Download</a>";
    }
    
    public function asIddate($date)
    {
        if ($date == null) {
            return "-";
        }

        $withHour = true;
        $arr = explode(" ", $date);
        if (count($arr) == 1) {
            $withHour = false;
        }
        $time = strtotime($date);
        $padTime = str_pad($time, 12, "0", STR_PAD_LEFT);
        return ($withHour ? date("d ", $time) . Tanggal::getBulan(date($time)) . date(" Y, H:i", $time) : date("d ", $time) . Tanggal::getBulan(date($time)) . date(" Y", $time));
    }

    public function asRp($value)
    {
        if ($value != "") {
            return Angka::toReadableHarga($value);
        } else {
            return "-";
        }

    }

}