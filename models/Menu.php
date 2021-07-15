<?php

namespace app\models;

use Yii;
use \app\models\base\Menu as BaseMenu;

/**
 * This is the model class for table "menu".
 */
class Menu extends BaseMenu
{
    public function getIconNoPrefix(){
        return substr($this->icon, 3);
    }
}
