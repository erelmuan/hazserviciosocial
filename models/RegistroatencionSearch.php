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
    public $area;
    public $localidad;
    public $barrio;
    public $usuario;
    public $tiporeg;
    public $fecha_desde;
    public $fecha_hasta;
    public $nota;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_paciente', 'id_tiporeg', 'id_organismo', 'numero_nota', 'id_usuario'], 'integer'],
            [['fecha_desde','fecha_hasta','motivo', 'fecha'], 'safe'],
            ['fecha', 'date', 'format' => 'dd/MM/yyyy'],
            [['num_nota_automatico' ,'nota'], 'boolean'],
            [['paciente','tiporeg','organismo','usuario','localidad','barrio','area'], 'safe'],

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
        ->leftJoin('organismo', 'organismo.id = registroatencion.id_organismo')
        ->leftJoin('area', 'area.id = registroatencion.id_area')
         //condidero a los registros que tienen pacientes sin importar si tienen domicilio
        ->leftJoin('historicodomicilio',  'registroatencion.id =historicodomicilio.id_registroatencion')
        ->leftJoin('localidad',  'localidad.id =historicodomicilio.id_localidad')
        ->leftJoin('barrio',  'barrio.id =historicodomicilio.id_barrio')
        ->orderBy(['registroatencion.id' => SORT_DESC])
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
            'registroatencion.id' => $this->id,
            'fecha' => $this->fecha,
            'numero_nota' => $this->numero_nota,
            'id_usuario' => $this->id_usuario,
            'id_area' => $this->id_area,
            'num_nota_automatico' => $this->num_nota_automatico,
            'id_anionota' => $this->id_anionota,

        ]);
        if (is_numeric($this->paciente)){
            $query->orFilterWhere(["paciente.num_documento"=>$this->paciente]);
             }
        else {
            $query->andFilterWhere(['ilike', '("paciente"."apellido")',strtolower($this->paciente)]);
        }

        if ($this->nota ==1){
          $query->andWhere(['not', ['numero_nota' => null]]);
             }
        if ($this->nota !="" && $this->nota ==0){
          $query->andWhere(['is', 'numero_nota', new \yii\db\Expression('null')]);
        }
        $query->andFilterWhere(['ilike', 'motivo', $this->motivo])
        ->andFilterWhere(['ilike', 'usuario.nombre', $this->usuario])
        ->andFilterWhere(['ilike', 'tiporeg.descripcion', $this->tiporeg])
        ->andFilterWhere(['ilike', 'localidad.nombre', $this->localidad])
        ->andFilterWhere(['ilike', 'barrio.nombre', $this->barrio])
        ->andFilterWhere(['ilike', 'organismo.nombre', $this->organismo])
        ->andFilterWhere(['ilike', 'area.nombre', $this->area])

        ->andFilterWhere(['>=', 'fecha', $this->fecha_desde])
        ->andFilterWhere(['<', 'fecha', $this->fecha_hasta]);


        return $dataProvider;
    }
}
