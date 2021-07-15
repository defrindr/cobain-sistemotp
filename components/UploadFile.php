<?php

namespace app\components;

use Yii;

trait UploadFile
{
    public static function uploadImage($file, $uploadTo = "random")
    {
        $jenis_konten = $file->type;

        if (preg_match("/image/", $jenis_konten)) {
            $realpath_dir = Yii::getAlias("@webroot/uploads/{$uploadTo}/");
            if (file_exists($realpath_dir) == false) {
                mkdir($realpath_dir, 0777, true);
            }


            $file_sementara = $file->tempName;
            $ext = end(explode(".", $file->name));

            $namaFile = sha1(rand(0, 9999)) . ".{$ext}";
            $file_dipermanenkan = $realpath_dir . $namaFile;
            $filename = $file_sementara;
            $percent = 1;

            // jiplak resolusi
            // pendeteksian ini masih bisa lolos dgn teknik RGB
            $size = getimagesize($filename); //diambil dari file temp, bukan $_FILE['mime']
            $width = $size[0];
            $height = $size[1];
            $mime = $size['mime'];

            //jika butuh memperkecil gambar
            $new_width = $width * $percent;
            $new_height = $height * $percent;
            // patenkan width
            $new_width = 450;
            $new_height = $width == 0 ? 0 : $height * $new_width / $width;

            // buat gambar baru

            if (preg_match('/png|jpeg|jpg|gif/', $mime)) {
                $image_p = imagecreatetruecolor($new_width, $new_height);
                imagealphablending($image_p, false);
                imagesavealpha($image_p, true);
                $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                imagefilledrectangle($image_p, 0, 0, $new_width, $new_height, $transparent);
                ini_set('memory_limit', '256M');
                if ((preg_match('/jpg/', $mime)) || (preg_match('/jpeg/', $mime))) {
                    $image = imagecreatefromjpeg($filename);
                }
                if (preg_match('/png/', $mime)) {
                    $image = imagecreatefrompng($filename);
                }
                if (preg_match('/gif/', $mime)) {
                    $image = imagecreatefromgif($filename);
                }

            }

            if (!@imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
                $image_p = imagecreate(200, 100);
                $bg = imagecolorallocate($image_p, 255, 255, 255);
                $black = imagecolorallocate($image_p, 0, 0, 0);
                imagestring($image_p, 5, 2, 2, 'Gambar Korupsi', $black);
            }

            // Output
            imagepng($image_p, $file_dipermanenkan, 0);
            return (object) [
                "success" => true,
                "filename" => "{$uploadTo}/{$namaFile}",
            ];
        } else {
            // return static::uploadFile($file, $uploadTo);
            return (object) [
                "success" => false,
                "message" => "Jenis file yang anda unggah bukan gambar.",
            ];
        }
    }

    public static function uploadFile($file, $uploadTo = "random"){
        $realpath_dir = Yii::getAlias("@webroot/uploads/{$uploadTo}/");
        if (file_exists($realpath_dir) == false) {
            mkdir($realpath_dir, 0777, true);
        }

        $ext = end(explode(".", $file->name));
        $namaFile = sha1(rand(0, 9999)) . ".{$ext}";

        $file->saveAs("{$realpath_dir}/{$namaFile}");
        return (object) [
            "success" => true,
            "filename" => "{$uploadTo}/{$namaFile}",
        ];
    }

    public static function deleteOne($filename)
    {
        $folder_path = Yii::getAlias("@webroot/uploads/");
        $default = Yii::getAlias("@webroot/uploads/default.png");
        $real_path = Yii::getAlias("@webroot/uploads/$filename");
        $existing_file = file_exists($real_path);

        if ($existing_file) {
            if ($folder_path != $real_path && $real_path != $default) {
                unlink($real_path);
            }
        }
    }
}
