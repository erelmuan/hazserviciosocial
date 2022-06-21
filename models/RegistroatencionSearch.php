<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Registroatencion;

/**
 * RegistroatencionSearch represents the model behind the search form about `app\models\Registroatencion`.
 */
class RegistroatencionSearch extends Registroatencion
{
  //son necesarias las variables y tambien la modificacion en el archivo _columns.php
  // en los atributos
    public $paciente;
    public $organismo;
    public $localidad;
    public $barrio;
    public $usuario;
    public $tiporeg;
    public $fecha_desde;
    public $fecha_hasta;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_paciente', 'id_tiporeg', 'id_organismo', 'numero_nota', 'id_usuario'], 'integer'],
            [['fecha_desde','fecha_hasta','motivo', 'fecha'], 'safe'],
            ['fecha', 'date', 'format' => 'dd/MM/yyyy'],
            [['paciente','tiporeg','organismo','usuario','localidad','barrio'], 'safe'],

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
        $query = Registroatencion::find()
        ->innerJoinWith('paciente', true)
        ->innerJoinWith('tiporeg', 'tiporeg.id = registroatencion.id_tiporeg')
        ->innerJoinWith('usuario', 'usuario.id = registroatencion.id_usuario')
        ->innerJoinWith('organismo', 'organismo.id = registroatencion.id_organismo')
        //condidero a los registros que tienen pacientes sin importar si tienen domicilio
        ->leftJoin('domicilio',  'paciente.id =domicilio.id_paciente')
        ->leftJoin('localidad',  'localidad.id =domicilio.id_localidad')
        ->leftJoin('barrio',  'barrio.id =domicilio.id_barrio')

        ;

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
            'fecha' => $this->fecha,
            'numero_nota' => $this->numero_nota,
        ]);
        if (is_numeric($this->paciente)){
            $query->orFilterWhere(["paciente.num_documento"=>$this->paciente]);
             }
        else {
            $query->andFilterWhere(['ilike', '("paciente"."apellido")',strtolower($this->paciente)]);

        }
        $query->andFilterWhere(['ilike', 'motivo', $this->motivo])
        ->andFilterWhere(['ilike', 'usuario.nombre', $this->usuario])
        ->andFilterWhere(['ilike', 'tiporeg.descripcion', $this->tiporeg])
        ->andFilterWhere(['ilike', 'localidad.nombre', $this->localidad])
        ->andFilterWhere(['ilike', 'barrio.nombre', $this->barrio])
        ->andFilterWhere(['ilike', 'organismo.nombre', $this->organismo])
        ->andFilterWhere(['>=', 'fecha', $this->fecha_desde])
        ->andFilterWhere(['<', 'fecha', $this->fecha_hasta]);


        return $dataProvider;
    }
}
