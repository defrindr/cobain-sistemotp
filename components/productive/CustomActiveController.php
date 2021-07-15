<?php
namespace app\components;

use app\models\Action;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class CustomActiveController extends Controller
{
    use \app\components\productive\Messages;
    
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return Action::getAccess($this->id);
    }

    /**
     * Displays a single SuratBeritaAcaraSosialisasi model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

        if($model->hasAttribute('status_id')) :
            if ($model->status_id == $model->getContainer('statusMax')):
                $this->messageValidationError();
                return $this->redirect(Url::previous());
            endif;
        endif;

        try {
            $response = $model->updateModel();

            if ($response['success']):
                return $this->redirect(Url::previous());
            else:
                goto end;
            endif;
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
            
            if($model->hasAttribute('status_id')) :
                if ($model->status_id != 1):
                    $this->messageDeleteForbidden();
                    return $this->redirect(['index']);
                endif;
            endif;

            $modelRelations = $model->initializeChildren();

            foreach ($modelRelations as $relational_values):
                foreach ($relational_values as $relational):
                    $relational->delete();
                endforeach;
            endforeach;

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
}
