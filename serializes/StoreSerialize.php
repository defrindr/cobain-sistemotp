<?php

namespace app\serializes;

use Yii;
use yii\rest\Serializer;

class SampleSerialize extends Serializer
{
    public function serialize($data)
    {
        $parent = parent::serialize($data);
        $list = ["api/store"];
        if (in_array(Yii::$app->request->getPathInfo(), $list)) {
            $parent['success'] = true;
        }
        
        return $parent;
    }
}
