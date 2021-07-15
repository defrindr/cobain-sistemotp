<?php
/**
 * Created by PhpStorm.
 * User: feb
 * Date: 27/01/16
 * Time: 13.54
 */

namespace app\components;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use yii\db\ActiveRecord;

class WordProcessor
{
    private $template = "";

    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

    /**
     * @var PhpWord
     */
    private $phpWord;

    /**
     * WordProcessor constructor.
     */
    public function __construct($template)
    {
        Settings::setTempDir(\Yii::getAlias("@word_output/"));

        $this->template = $template;
        $this->phpWord = new PhpWord();
        $this->templateProcessor = $this->load();
    }

    private function load()
    {
        $file = \Yii::getAlias("@word_template/" . $this->template);

        return $this->phpWord->loadTemplate($file);
    }

    /**
     * Mereplace semua tag berdasarkan model yang diberikan
     * @param $model ActiveRecord
     */
    private $modelId = 0;
    public function setBaseModel($model)
    {
        $registeredVariables = $this->templateProcessor->getVariables();
        foreach ($registeredVariables as $key => $registeredVariable) {
            $str = strip_tags($registeredVariable);
            $hasil = "";
            $d = "\$hasil = " . $this->processString($str) . ";";
            //echo $d;

            try {
                eval($d);
            } catch (\yii\base\UnknownPropertyException $ex) {
                //print_r($ex->getMessage());
                $hasil = "ERROR";
            }
            if (0 != "ERROR") {

            }

            if (is_string($hasil) && $hasil == "ERROR") {
                continue;
            } else {
                //echo $hasil . "<br/>";

                $hasil = htmlspecialchars($hasil);
                //$hasil = str_replace("&","dan", $hasil);
                $this->templateProcessor->setValue($str, $hasil);
            }
        }
        $this->modelId = $model->id;
    }

    /**
     * Mereplace sebuah tag yang ditentukan
     * @param $tag string
     * @param $value string
     */
    public function setValue($tag, $value)
    {

        $value = htmlspecialchars($value);
        $this->templateProcessor->setValue($tag, $value);
    }

    public function cloneRow($tag, $multiple)
    {
        $this->templateProcessor->cloneRow($tag, $multiple);
    }

    /**
     * Menampilkan tanda tangan dengan tag ${tanda_tangan}
     * @param $isShow bool status tampil tanda tangan
     */
    public function setSignature($isShow, $fileName = "blank.png", $tag = "tanda_tangan", $size = '250')
    {
        $tandaTanganPath = \Yii::getAlias("@root_path/tanda_tangan/$fileName");
        if ($fileName == "" || !file_exists($tandaTanganPath)) {
            $tandaTanganPath = \Yii::getAlias("@root_path/tanda_tangan/blank.png");
        }
        if ($isShow) {
            NodeLogger::sendLog("Menampilkan Tanda Tangan");
            $this->templateProcessor->setImageValue($tag, array('src' => $tandaTanganPath, 'swh' => $size));
        }
    }

    /**
     * Menampilkan tanda tangan dengan tag ${tanda_tangan}
     * @param $imagePath string full path dari gambar
     */
    public function setImage($imagePath, $tag = "image", $size = '250')
    {
        if (file_exists($imagePath)) {

            try {
                $this->templateProcessor->setImageValue($tag, array('src' => $imagePath, 'swh' => $size));
            } catch (\Exception $ex) {
                $this->setValue($tag, '');
            }

        } else {
            $this->setValue($tag, '');
        }

    }

    /**
     * Menampilkan pasfoto dengan tag ${pasfoto}
     * @param $url string url pasfoto
     */
    public function setPasfoto($url)
    {
        $size = '150';
        $tag = 'pasfoto';

        NodeLogger::sendLog([
            "function" => "WordProcessor::setPasfoto",
            "path" => $url,
            "tag" => $tag,
            "size" => $size,
        ]);

        $foto = \Yii::getAlias($url);
        if (!file_exists($foto)) {
            $foto = \Yii::getAlias("@root_path/uploads/default.png");
        }

        try {
            $this->templateProcessor->setImageValue($tag, array('src' => $foto, 'swh' => $size));
        } catch (\Exception $ex) {
            $this->setValue($tag, '');
        }
    }

    public function setModelID($modelId)
    {
        $this->modelId = $modelId;
    }

    /**
     * Mendownload kepada user
     */
    public function download($asPdf = true)
    {
        $namaFile = date("Y-m-d-") . $this->template;
        $this->templateProcessor->saveAs(\Yii::getAlias("@word_output/" . $namaFile));
        \Yii::$app->controller->redirect(\Yii::getAlias("@word_output_url/") . $namaFile);
    }

    private function processString($str)
    {
        if (ctype_lower(substr($str, 0, 1))) {
            //biasa
            return "\$model->" . implode("->", explode(".", $str));
        } else {
            $arr = explode(".", $str);
            $first = array_shift($arr);
            return $first . "::" . implode("->", $arr);
        }
    }
}
