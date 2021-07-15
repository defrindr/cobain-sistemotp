<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WilayahDesa;

/**
 * WilayahDesaSearch represents the model behind the search form about `app\models\WilayahDesa`.
 * Modified By Defri Indras
 */
class WilayahDesaSearch extends WilayahDesa{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'kecamatan_id', 'nama'], 'safe'],
    ];
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        return $this->baseSearch("web",$params);
    }

    public function searchApi($params){
        return $this->baseSearch("api",$params);
    }

    /**
    * Creates data provider instance with search query applied
    *
    * @param array $params
    *
    * @return ActiveDataProvider
    */
    public function baseSearch($type,$params)
    {
        $query = WilayahDesa::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $formData = ($type == "api") ? '' : null;

        $this->load($params, $formData);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'kecamatan_id', $this->kecamatan_id])
            ->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}