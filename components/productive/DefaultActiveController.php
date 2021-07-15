<?php
namespace app\components\productive;

use app\models\Action;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class DefaultActiveController extends Controller
{
    use Messages;
    
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
}
