<?php
/**
 * Customizable controller class.
 * Modified by Defri Indra
 */
echo "<?php\n";
?>

namespace <?= $generator->controllerNs ?>\api;

/**
 * This is the class for REST controller "<?= $controllerClassName ?>".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class <?= $controllerClassName ?> extends \yii\rest\ActiveController
{
    public $modelClass = '<?= $generator->modelClass ?>';

    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication'] = [
            "class" => "\app\components\CustomAuth",
            "except" => ["index", "view"]
        ];

        return $parent;
    }

    /**
    * @inheritdoc
    */
    public function actions()
    {
        $parent = parent::actions();
        $parent['index']['prepareDataProvider'] = [$this,"prepareDataProvider"];
        
        unset($parent['index']);
        unset($parent['view']);
        unset($parent['create']);
        unset($parent['update']);
        unset($parent['delete']);

        return $parent;
    }

    /**
    * Custom data Provider
    */
	public function prepareDataProvider()
    {
        $searchModel = new <?= $generator->searchModelClass ?>();
        $dataProvider = $searchModel->searchApi(\Yii::$app->request->get());
        
        return $dataProvider;
    }
}
