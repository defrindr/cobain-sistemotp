<?php

namespace app\components\annex;

use yii\bootstrap\BootstrapAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @inheritdoc
 */
class Tabs extends \yii\bootstrap\Tabs
{
    public $navType = 'nav-pills nav-justified';
    /**
     * Register assetBundle
     */
    public static function registerAssets()
    {
        // BootstrapAsset::register(\Yii::$app->controller->getView());
    }
    

    /**
     * Sets the first visible tab as active.
     *
     * This method activates the first tab that is visible and
     * not explicitly set to inactive (`'active' => false`).
     * @since 2.0.7
     */
    protected function activateFirstVisibleTab()
    {
        dd($this->items);
        foreach ($this->items as $i => $item) {
            $active = ArrayHelper::getValue($item, 'active', null);
            $visible = ArrayHelper::getValue($item, 'visible', true);
            if ($visible && $active !== false) {
                $this->items[$i]['active'] = true;
                return;
            }
        }
    }

    /**
     * Remember active tab state for this URL
     */
    public static function rememberActiveState()
    {
        // self::registerAssets();
        $js = <<<JS
            jQuery("#relation-tabs > li > a").on("click", function () {
                setStorage(this);
            });

            jQuery(document).on('pjax:end', function() {
               setStorage($('#relation-tabs .active A'));
            });

            jQuery(window).on("load", function () {
               initialSelect();
            });
JS;

        if (\Yii::$app->request->isAjax) {
            echo "<script type='text/javascript'>{$js}</script>";
        } else {
            // Register cookie script
            \Yii::$app->controller->getView()->registerJs(
                $js,
                View::POS_END,
                'rememberActiveState'
            );
        }
    }

    /**
     * Clear the localStorage of your browser
     */
    public static function clearLocalStorage()
    {
        // TODO @c.stebe - This removes all cookies, eg. the ones set from Yii 2 debug toolbar
        /*\Yii::$app->controller->getView()->registerJs(
            'window.localStorage.clear();',
            View::POS_READY,
            'clearLocalStorage'
        );*/
    }
}