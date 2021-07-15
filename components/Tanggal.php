<?php
namespace app\components;

class Tanggal {
    const MONTH = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    public static function reverse($date) {
        $arr = explode(" ", $date);
        if (count($arr) == 1) {
            $tgl = $arr[0];
            $tglArr = explode("-", $tgl);
            $tgl = implode("-", array_reverse($tglArr));
            return $tgl;
        } else {
            $tgl = $arr[0];
            $jam = $arr[1];
            $tglArr = explode("-", $tgl);
            $tgl = implode("-", array_reverse($tglArr));
            return $tgl . " " . $jam;
        }
    }
    
    public static function toReadableDate($date, $withSpan=TRUE, $fullMonthName = false){
        if($date == NULL) return "-";
        $withHour = TRUE;
        $arr = explode(" ", $date);
        if(count($arr)==1){
            $withHour = FALSE;
        }
        $time = strtotime($date);
        $padTime = str_pad($time, 12, "0", STR_PAD_LEFT);
        return ($withSpan?"<span style='display:none'>{$padTime}</span>":"").($withHour?date("d ", $time).static::getBulan(date($time), $fullMonthName).date(" Y, H:i", $time):date("d ", $time).static::getBulan(date($time), $fullMonthName).date(" Y", $time));
    }

    public static function getBulan($time, $fullName = false){
        $arrBulan = array("", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
        $arrBulanFull = static::MONTH;
        $bulan = date("n", $time);
        return ($fullName) ? $arrBulanFull[1 * $bulan] : $arrBulan[1 * $bulan];
    }

    public static function getJumlahHari($tahun, $bulan){
        $jml = array(
            "01" => 31,
            "02" => 28,
            "03" => 31,
            "04" => 30,
            "05" => 31,
            "06" => 30,
            "07" => 31,
            "08" => 31,
            "09" => 30,
            "10" => 31,
            "11" => 30,
            "12" => 31,
        );
        
        $bulan = str_pad($bulan, 2, "0", STR_PAD_LEFT);
        
        $jmlHari = $jml[$bulan];

        if ($tahun % 4 == 0 && $bulan == "02") {
            $jmlHari = 29;
        }
        
        return $jmlHari;
    }

    public static function timeElapsedString($ptime) {
        $etime = time() - $ptime;

        if ($etime < 1) {
            return '0 seconds';
        }

        $a = array(365 * 24 * 60 * 60 => 'tahun',
            30 * 24 * 60 * 60 => 'bulan',
            24 * 60 * 60 => 'hari',
            60 * 60 => 'jam',
            60 => 'menit',
            1 => 'detik'
        );
        $a_plural = array('tahun' => 'tahun',
            'bulan' => 'bulan',
            'hari' => 'hari',
            'jam' => 'jam',
            'menit' => 'menit',
            'detik' => 'detik'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' yang lalu';
            }
        }
    }
    
    public static function getWeekDateRange($date){
        $tahun = date("Y", strtotime($date));
        $bulan = date("m", strtotime($date));
        $arrOfWeek = Tanggal::weekOfMonth($tahun, $bulan);
        //return "JML".count($arrOfWeek);
        $num = 1;
        foreach ($arrOfWeek as $week) {
            //echo $week[0]." ++ ".$date." ++ ".$week[1]."<br>";
            if(strtotime($week[0]) <= strtotime($date) && strtotime($date) <= strtotime($week[1])){
                return $week;
            }
            $num++;
        }
        return NULL;
    }
    
    public static function getDateRange($year, $month, $weekNum){
        $arrOfWeek = Tanggal::weekOfMonth($year, $month);
        //return "JML".count($arrOfWeek);
        $num = 0;
        foreach ($arrOfWeek as $week) {
            if($num == $weekNum){
                return $week;
            }
            $num++;
        }
        return NULL;
    }
    
    public static function getWeekNum($date){
        $tahun = date("Y", strtotime($date));
        $bulan = date("m", strtotime($date));
        $arrOfWeek = Tanggal::weekOfMonth($tahun, $bulan);
        //return "JML".count($arrOfWeek);
        $num = 0;
        foreach ($arrOfWeek as $week) {
            //echo $week[0]." ++ ".$date." ++ ".$week[1]."<br>";
            if(strtotime($week[0]) <= strtotime($date) && strtotime($date) <= strtotime($week[1])){
                return $num;
            }
            $num++;
        }
        return -1;
    }

    public static function weekOfMonth($year, $month) {
        //$year = str_pad($year, 2, "0", STR_PAD_LEFT);
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
        
        $jml = array(
            "01"=>31,
            "02"=>28,
            "03"=>31,
            "04"=>30,
            "05"=>31,
            "06"=>30,
            "07"=>31,
            "08"=>31,
            "09"=>30,
            "10"=>31,
            "11"=>30,
            "12"=>31,
        );
        
        $jmlHari = $jml[$month];
        $noAwalHari = 1; //senin
        
        $arrayHari = array();
        
        for($i=1;$i<=$jmlHari;$i++){
            $hari = $year."-".$month."-".str_pad($i, 2, "0", STR_PAD_LEFT);
            if(strtotime($hari) > strtotime(date("Y-m-d"))){
                //break;
            }
            $noHari = date("N", strtotime($hari));
            if($noHari == $noAwalHari){
                //echo $noHari." - ".$noAwalHari." - ".$hari."<br>";
                $kemudian = date("Y-m-d", strtotime($hari." +6 days"));
                
                $obj = array(
                    $hari,
                    $kemudian
                );
                $arrayHari[] = $obj;
            }
        }
        
        return $arrayHari;
    }
    
    public static function isInsideRange($date, $range1, $range2) {
        if(strtotime($range1) <= strtotime($date) && strtotime($date) <= strtotime($range2)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public static function getUmur($tgl_lahir) {
        $tz = new \DateTimeZone('Asia/Jakarta');
        //echo $pasien->tanggal_lahir."<br>";
        if($tgl_lahir != NULL){
            $diff = \DateTime::createFromFormat('Y-m-d', $tgl_lahir, $tz)->diff(new \DateTime('now', $tz));
            return $diff->y;
        }
        return 0;
    }
    
    public static function getUmurLengkap($tgl_lahir) {
        //$tz = new DateTimeZone('Asia/Jakarta');
        //echo $pasien->tanggal_lahir."<br>";
        if($tgl_lahir != NULL){
            /*
            $diff = DateTime::createFromFormat('Y-m-d', $tgl_lahir, $tz)->diff(new DateTime('now', $tz));
            $year = $diff->y;
            $month = $diff->m;
            $day = $diff->d;
            unset($tz);
            unset($diff);
            return array($year, $month, $day);
             */
            
            $date1 = new \DateTime($tgl_lahir);
            $date2 = new \DateTime(date("Y-m-d"));
            $diff = $date1->diff($date2);
            $year = $diff->y;
            $month = $diff->m;
            $day = $diff->d;
            unset($diff);
            return array($year, $month, $day);
        }
        return NULL;
    }
    
    public static function getUmurKunjungan($tgl_lahir, $tgl_kunjungan){
        $diff = date_diff(date_create($tgl_lahir), date_create($tgl_kunjungan));
        $umur = "";
        if($diff->y != 0){
            $umur .= $diff->y. " th";
        }else if($diff->m != 0){
            $umur .= $diff->m. " bln";
        }else{
            $umur .= $diff->d. " hari";
        }
        return $umur;
    }
    
    public static function getTanggalLahir($tahun, $bulan, $hari) {
        $hari = ($hari > 31)?0:$hari;
        $bulan = ($hari > 12)?0:$bulan;
        $tahun = ($hari > 200)?80:$tahun;
        
        $time = strtotime(date("Y-m-d"). " -".$tahun." year");
        $time = strtotime(date("Y-m-d", $time). " -".$bulan." month");
        $time = strtotime(date("Y-m-d", $time). " -".$hari." day");
        return date("Y-m-d", $time);
    }

    public static function formatTime($time){
        $time = date("Y-m-d"). " ". $time;

        $unixtimestamp = strtotime($time);
        return date("H:i A", $unixtimestamp);
    }

    public static function secondToTime($second){
        $template = "";
        if($second >= 3600){
            $temp = floor($second / 3600);
            $second = $second % 3600;
            $template .= "{$temp} Jam ";
        }
        if($second >= 60){
            $temp = floor($second / 60);
            $second = $second % 60;
            $template .= "{$temp} Menit ";
        }
        if($second != 0){
            $template .= "{$second} Detik";
        }

        if($template == ""){
            return "-";
        }

        return $template;        
    }

    public static function greaterThan($main, $comparing, $with_equal = false){
        $main = strtotime($main);
        $comparing = strtotime($comparing);
        return ($with_equal) ? $main > $comparing : $main >= $comparing ;
    }
    
    public static function lessThan($main, $comparing, $with_equal = false){
        $main = strtotime($main);
        $comparing = strtotime($comparing);
        return ($with_equal) ? $main < $comparing : $main <= $comparing ;
    }

    public static function getDayName($date, $fullName = false){

        $arr = [
            "Sunday" => "Minggu",
            "Monday" => "Senin",
            "Tuesday" => "Selasa",
            "Wednesday" => "Rabu",
            "Thursday" => "Kamis",
            "Friday" => "Jum'at",
            "Saturday" => "Sabtu",
        ];
        
        $arr2 = [
            "Sunday" => "Ming",
            "Monday" => "Sen",
            "Tuesday" => "Sel",
            "Wednesday" => "Rab",
            "Thursday" => "Kam",
            "Friday" => "Jum",
            "Saturday" => "Sab",
        ];

        $day = date("l", strtotime("{$date}"));

        return ($fullName) ? $arr[ $day ] : $arr2[ $day ] ;
    }
    
    public static function getTimeReadable($time, $withSuffix = false){
        $template="H:i";
        if($withSuffix) $template="H:i A";
        return date($template, strtotime($time));
    }
}
