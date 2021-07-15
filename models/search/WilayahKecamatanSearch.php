<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WilayahKecamatan;

/**
 * WilayahKecamatanSearch represents the model behind the search form about `app\models\WilayahKecamatan`.
 * Modified By Defri Indras
 */
class WilayahKecamatanSearch extends WilayahKecamatan{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'kota_id', 'nama'], 'safe'],
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
        $query = WilayahKecamatan::find();

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
            ->andFilterWhere(['like', 'kota_id', $this->kota_id])
            ->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}