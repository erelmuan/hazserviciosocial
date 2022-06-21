<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registroatencion".
 *
 * @property int $id
 * @property int $id_paciente
 * @property string $motivo
 * @property int $id_tiporeg
 * @property int $id_organismo
 * @property string $fecha
 * @property int $numero_nota
 * @property int $id_usuario
 *
 * @property Organismo $organismo
 * @property Paciente $paciente
 * @property Tiporeg $tiporeg
 * @property Usuario $usuario
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Registroatencion extends \yii\db\ActiveRecord
{

    public function behaviors() {
        return array(
            'AuditoriaBehaviors' => array(
                'class' => AuditoriaBehaviors::className() ,
            ) ,
        );
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registroatencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_paciente', 'motivo', 'id_tiporeg', 'id_organismo', 'id_usuario','fecha'], 'required'],
            [['id_paciente', 'id_tiporeg', 'id_organismo', 'numero_nota', 'id_usuario'], 'default', 'value' => null],
            [['id_paciente', 'id_tiporeg', 'id_organismo', 'numero_nota', 'id_usuario'], 'integer'],
            [['motivo'], 'string'],
            [['fecha'], 'safe'],
            [['id_paciente'], 'unique'],
            [['id_organismo'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['id_organismo' => 'id']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['id_paciente' => 'id']],
            [['id_tiporeg'], 'exist', 'skipOnError' => true, 'targetClass' => Tiporeg::className(), 'targetAttribute' => ['id_tiporeg' => 'id']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    public function attributeColumns()
    {


        return [
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'id',
          ],
          [
              'attribute' => 'numero_nota',
              'width' => '50px',
          ],
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'paciente',
              'width' => '170px',
              'value' => 'getLink',
               'filterInputOptions' => ['class' => 'form-control',  'placeholder' => 'DNI o apellido'],
               'format' => 'raw',

          ],
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'motivo',

          ],
          [
              //nombre
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'fecha',
              'label'=> 'Fecha',
              'format' => ['date', 'd/M/Y'],
              'filterInputOptions' => [
                  'id' => 'fecha1',
                  'class' => 'form-control',
                  'autoclose'=>true,
                  'format' => 'dd/mm/yyyy',
                  'startView' => 'year',
                  'placeholder' => 'd/m/aaaa'

              ]

          ],

          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'tiporeg',
              'label'=> 'Tipo',
              'value'=>'tiporeg.descripcion'
          ],
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'organismo',
              'width' => '170px',
              'value' => 'getLinkdos',
               'filterInputOptions' => ['class' => 'form-control',  'placeholder' => 'Nombre del organismo'],
               'format' => 'raw',

          ],

          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'usuario',
              'label'=> 'Usuario',
              'value'=>'usuario.nombre'
          ],
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'localidad',
              'label'=> 'Localidad',
              'value'=>'paciente.domicilio.localidad.nombre'
          ],
          [
              'class'=>'\kartik\grid\DataColumn',
              'attribute'=>'barrio',
              'label'=> 'Barrio',
              'value'=>'paciente.domicilio.barrio.nombre'
          ],

        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_paciente' => 'Id Paciente',
            'motivo' => 'Motivo',
            'id_tiporeg' => 'Id Tiporeg',
            'id_organismo' => 'Id Organismo',
            'fecha' => 'Fecha',
            'numero_nota' => 'Numero Nota',
            'id_usuario' => 'Id Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismo()
    {
        return $this->hasOne(Organismo::className(), ['id' => 'id_organismo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['id' => 'id_paciente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiporeg()
    {
        return $this->hasOne(Tiporeg::className(), ['id' => 'id_tiporeg']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'id_usuario']);
    }
}
