<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Area;

/**
 * AreaSearch represents the model behind the search form about `app\models\Area`.
 */
class AreaSearch extends Area
{
  public $organismo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string'],
            [['id', 'id_organismo'], 'integer'],
            [['nombre','organismo'], 'safe'],

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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Area::find()->innerJoinWith('organismo', true);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'area.id' => $this->id,
        ]);
        $query->andFilterWhere(['ilike', 'area.nombre', $this->nombre])
        ->andFilterWhere(['ilike', 'organismo.nombre', $this->organismo]);

        return $dataProvider;
    }
}
