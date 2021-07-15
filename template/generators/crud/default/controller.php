<?php

use yii\helpers\StringHelper;

/*
 * This is the template for generating a CRUD controller class file.
 *
 * @var yii\web\View $this
 * @var schmunk42\giiant\generators\crud\Generator $generator
 */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$searchModelClassName = $searchModelClass;
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
    $searchModelClassName = $searchModelAlias;
}

// TODO: improve detetction of NOSQL primary keys
if ($generator->getTableSchema()) {
    $pks = $generator->getTableSchema()->primaryKey;
} else {
    $pks = ['_id'];
}

$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
echo "<?php\n";
?>
/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/
namespace <?=StringHelper::dirname(ltrim($generator->controllerClass, '\\'))?>\base;

use <?=ltrim($generator->modelClass, '\\')?>;
<?php if ($searchModelClass !== ''): ?>
use <?=ltrim(
    $generator->searchModelClass,
    '\\'
)?><?php if (isset($searchModelAlias)): ?> as <?=$searchModelAlias?><?php endif?>;
<?php endif;?>
use <?=ltrim($generator->baseControllerClass, '\\')?>;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * <?=$controllerClass?> implements the CRUD actions for <?=$modelClass?> model.
 **/
class <?=$controllerClass?> extends \app\components\productive\DefaultActiveController
{
<?php
$traits = $generator->baseTraits;
if ($traits) {
    echo "use {$traits};";
}
?>

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;
    

    /**
    * Lists all <?=$modelClass?> models.
    * @return mixed
    */
    public function actionIndex()
    {
        <?php if ($searchModelClass !== '') {?>
$searchModel  = new <?=$searchModelClassName?>;
        $dataProvider = $searchModel->search($_GET);
        <?php } else { ?>
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => <?=$modelClass?>::find(),
        ]);
        <?php }?>

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
    <?php if ($searchModelClass !== ''): ?>
        'searchModel' => $searchModel,
    <?php endif;?>
    ]);
    }

    /**
    * Creates a new <?=$modelClass?> model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
    public function actionCreate()
    {
        $model = new <?=$modelClass?>;
        $model->scenario = $model::SCENARIO_CREATE;
        
        try {
            if ($model->load($_POST)) :
                if($model->validate()):
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', $model->render());
    }
    
    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;
        
        try {
            if ($model->load($_POST)) :
                if($model->validate()):
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            $model->delete();

            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true):
            return $this->redirect(Url::previous());
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/'):
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        else:
            return $this->redirect(['index']);
        endif;
    }

    /**
    * Finds the <?=$modelClass?> model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * <?=implode("\n\t * ", $actionParamComments) . "\n"?>
    * @return <?=$modelClass?> the loaded model
    * @throws HttpException if the model cannot be found
    */
    protected function findModel(<?=$actionParams?>)
    {
    <?php
    if (count($pks) === 1) {
        $condition = '$' . $pks[0];
    } else {
        $condition = [];
        foreach ($pks as $pk) {
            $condition[] = "'$pk' => \$$pk";
        }
        $condition = '[' . implode(', ', $condition) . ']';
    }
    ?>
    if (($model = <?=$modelClass?>::findOne(<?=$condition?>)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
