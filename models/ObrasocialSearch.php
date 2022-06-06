<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Obrasocial;

/**
 * ObrasocialSearch represents the model behind the search form about `app\models\Obrasocial`.
 */
class ObrasocialSearch extends Obrasocial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'telefono','id_localidad'], 'integer'],
            [['sigla', 'denominacion', 'direccion', 'paginaweb', 'observaciones', 'correoelectronico'], 'safe'],
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
        $query = Obrasocial::find();

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
            'id' => $this->id,
            'telefono' => $this->telefono,
            'id_localidad' => $this->id_localidad,
        ]);

        $query->andFilterWhere(['like', 'sigla', $this->sigla])
            ->andFilterWhere(['like', 'denominacion', $this->denominacion])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'paginaweb', $this->paginaweb])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'correoelectronico', $this->correoelectronico]);

        return $dataProvider;
    }
}
