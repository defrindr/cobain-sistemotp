<?php
namespace app\components;

use app\components\Angka;
use app\models\MasterPenomoran;
use dmstr\helpers\Html;
use Yii;
use yii\helpers\ArrayHelper;

trait CustomActiveModel
{
    use \app\components\Messages;

    public static $container = [
        "baseModel" => "",
        "statusMax" => 1,
        "statusModel" => "",
        "attrNumber" => "tanggal",
        "modelChilds" => [],
        "modelChildValues" => [],
        "callback" => [],
    ];

    public function __construct($config = [])
    {
        parent::__construct($config);
        $model_status = $this->md_container["statusModel"];
        if($model_status) $this->md_container["statusMax"] = $model_status::find()->max('id');
        $this->setContainer($this->md_container);
    }

    public function initializeChildren()
    {
        return [];
    }

    public function initializeCallback(){
        return [];
    }

    private function initialize(){
        $this->md_container["modelChildValues"] = $this->initializeChildren();
        $this->md_container["callback"] = $this->initializeCallback();
        $this->setContainer($this->md_container);
    }

    public function statuses(){
        $model_status = $this->md_container["statusModel"];
        if($model_status) return $model_status::find();
        return null;
    }

    public function createModel($callback = null)
    {
        return $this->baseActionModel(self::$container['baseModel']::SCENARIO_CREATE, $callback);
    }

    public function updateModel($callback = null)
    {
        return $this->baseActionModel(self::$container['baseModel']::SCENARIO_UPDATE, $callback);
    }

    public function getContainer($item)
    {
        return $this->md_container[$item];
    }

    public function render()
    {
        if($this->scenario == self::$container['baseModel']::SCENARIO_UPDATE_STATUS):
            if($this->nomor_surat == null) :
                $this->nomor_surat = $this->getNomorUrut();
            endif;
        endif;
        $model = [
            "model" => $this,
        ];
        return array_merge($model, $this->initializeChildren());
    }

    public function setContainer($container)
    {
        self::$container = array_merge(self::$container, $container);
    }

    public function baseActionModel($scenario = "create", $callback = null)
    {
        $this->initialize();

        $model_childs = self::$container['modelChilds'];
        $model_base = self::$container['baseModel'];
        $model_callback = self::$container['callback'];
        extract(self::$container['modelChildValues']);

        $this->scenario = $scenario;
        $validate = false;

        if ($this->status_id == self::$container['statusMax']):
            $this->messageValidationError();
            goto end;
        endif;

        $transaction = Yii::$app->db->beginTransaction();
        if ($this->load($_POST)):
            if ($this->isNewRecord) :
                $this->status_id = 1;
            endif;

            if($callback) $callback($this); // callback

            $validate = $this->validate();

            if ($validate):
                $this->save();
            else:
                $transaction->rollBack();

                $this->messageValidationError();
                goto end;
            endif;

            // validate multiple value
            foreach ($model_childs as $className => $model_child_attribute):
                $variable_name = $model_child_attribute[0];
                $relational_key = $model_child_attribute[1];

                $oldKeyIds = ArrayHelper::map($$variable_name, 'id', 'id');
                $$variable_name = static::createMultiple($className);

                static::loadMultiple($$variable_name, \Yii::$app->request->post());

                if ($scenario != $model_base::SCENARIO_CREATE):
                    $deletedKeys = array_diff($oldKeyIds, array_filter(ArrayHelper::map($$variable_name, 'id', 'id')));
                    if (empty($deletedKeys) == false):
                        $className::deleteAll(['id' => $deletedKeys]);
                    endif;
                endif;

                foreach ($$variable_name as $child_index => $model_child):
                    $model_child_callback = $model_callback[$variable_name];
                    if($model_child_callback != null):
                        $model_child_callback($model_child);
                    endif;

                    $model_child->$relational_key = $this->id;
                    $$variable_name[$child_index] = $model_child;

                endforeach;

                $validate = $className::validateMultiple($$variable_name) && $validate;
            endforeach;

            if ($validate):
                foreach ($model_childs as $item):
                    $relational_variable = $item[0];
                    foreach ($$relational_variable as $index => $val):
                        $val->save();
                    endforeach;
                endforeach;

                $transaction->commit();

                if ($scenario == $model_base::SCENARIO_CREATE) {
                    $this->messageCreateSuccess();
                } else {
                    $this->messageUpdateSuccess();
                } else :
                $transaction->rollBack();
                $this->messageValidationError();
            endif;

        endif;

        end:
        $response = ["success" => $validate];

        foreach ($model_childs as $value):
            $relational_variable = $value[0];
            $response[$relational_variable] = $$relational_variable;
        endforeach;

        return $response;
    }

    public function getNomorUrut()
    {
        $attr = self::$container['attrNumber'];
        $tanggal = explode("-", date("Y-m-d", strtotime($this->$attr)));

        $replacement = [
            "id" => "",
            "bulan" => Angka::toRoman((int) $tanggal[1]),
            "tahun" => (int) $tanggal[0],
        ];

        $template = MasterPenomoran::findOne(['class' => static::class]);
        if ($template == null) {
            return null;
        }

        $template = $template->template;
        $cek_template = $template;

        foreach ($replacement as $key => $val) {
            $cek_template = str_replace('{' . $key . '}', $val, $cek_template);
        }

        $no = (int)static::find()->where([
            'like',
            'nomor_surat',
            $cek_template,
        ])->max("nomor_surat");

        if ($no == null) {
            $replacement["id"] = 1;
        } else {
            $no++;
            $replacement["id"] = $no;
        }

        foreach ($replacement as $key => $val) {
            $template = str_replace('{' . $key . '}', $val, $template);
        }

        return $template;
    }

    public function updateStatusModel($callback)
    {
        $modelStatus = self::$container["statusModel"];
        if($modelStatus == "") return false;

        $transaction = \Yii::$app->db->beginTransaction();

        $status_id = $this->status_id + 1;
        $model_status = $modelStatus::findOne(["id" => $status_id]);

        if ($model_status == null) :
            $this->messageBadRequest();
            return false;
        endif;

        $class_wo_namespace = str_replace("app\\models\\ ", "",
            strtolower(\app\components\Constanta::camel2space(static::class))
        );

        $name = ucwords($class_wo_namespace);

        $this->status_id = $status_id;

        if ($this->save()):
            $msg = ["name" => $name];

            $list = \app\components\Constanta::LIST_ATTR_WA;

            foreach ($list as $attr):
                if ($this->hasAttribute($attr)):
                    $msg[$attr] = $this->$attr;
                endif;
            endforeach;
            $msg["created_by"] = $this->createdBy->name;

            $response = $callback($msg);

            if ($response == false):
                $this->messageFailedSendWa();
                $transaction->rollBack();
                return false;
            endif;

            $transaction->commit();
            Yii::$app->getSession()->setFlash('success', 'Status updated!');
            return true;
        else:
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('warning', 'Status update failed!');
            return false;
        endif;
    }

    public static function getButtonStatus($id)
    {
        $status_model = self::$container['statusModel'];

        if($status_model == "") return "";

        $model = static::findOne($id);
        $status = $status_model::find()->where(['id' => ($model->status_id + 1)])->one();
        $allow_print = true;

        $btnUpdateStatus = Html::a('<i class="fa fa-check"></i>', ['update-status', 'id' => $id], [
            'class' => 'btn btn-success',
        ]);

        $btnCetak = Html::a('<i class="fa fa-print"></i>', ['print', 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'target' => '_blank',
        ]);

        $template = " ";
        $template .= ($status != null) ? "$btnUpdateStatus " : "";
        $template .= ($allow_print) ? "$btnCetak " : "";

        return $template;
    }

    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
                
            }
        }
        unset($model, $formName, $post);

        return $models;
    }

}
