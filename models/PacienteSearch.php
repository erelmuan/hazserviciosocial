<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Paciente;

/**
 * PacienteSearch represents the model behind the search form about `app\models\Paciente`.
 */
class PacienteSearch extends Paciente
{
  public $tipodoc;
  public $nacionalidad;

  /**
   * @inheritdoc
   */
  public function rules()
  {
      return [
        [['id', 'id_provincia','num_documento', 'id_localidad'], 'integer','except'=>'search'],
        // SCESNARIO //
        [['num_documento',],'integer','on'=>'search'],
        [['num_documento',],'required','on'=>'search'],
        ['fecha_nacimiento', 'date', 'format' => 'dd/MM/yyyy'],

        // SCESNARIO //
      [['nacionalidad','tipodoc','nombre', 'apellido',  'hc', 'sexo', 'fecha_nacimiento', 'direccion', 'cp', 'telefono', 'afiliado'], 'safe','except'=>'search'],
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
        $query = Paciente::find();

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
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'id_nacionalidad' => $this->id_nacionalidad,
            'id_tipodoc' => $this->id_tipodoc,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'sexo', $this->sexo])
            ->andFilterWhere(['ilike', 'apellido', $this->apellido])
            ->andFilterWhere(['ilike', 'hc', $this->hc])
            ->andFilterWhere(['like', 'num_documento', $this->num_documento]);

        return $dataProvider;
    }
}
