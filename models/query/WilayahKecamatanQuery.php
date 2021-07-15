<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\WilayahKecamatan]].
 *
 * @see \app\models\WilayahKecamatan
 */
class WilayahKecamatanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\WilayahKecamatan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\WilayahKecamatan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
