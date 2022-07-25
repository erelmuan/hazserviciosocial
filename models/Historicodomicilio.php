<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historicodomicilio".
 *
 * @property int $id
 * @property string $direccion
 * @property int $id_barrio
 * @property int $id_paciente
 * @property int $id_provincia
 * @property int $id_localidad
 * @property int $id_tipodom
 * @property string $fecha_baja
 * @property int $id_registroatencion
 * @property bool $principal
 * @property Barrio $barrio
 * @property Localidad $localidad
 * @property Paciente $paciente
* @property Provincia $provincia
 *
 * @property Registroatencion $registroatencion
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Historicodomicilio extends \yii\db\ActiveRecord
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
        return 'historicodomicilio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['direccion'], 'string'],
            [['id_barrio', 'id_paciente', 'id_provincia', 'id_localidad', 'id_tipodom', 'id_registroatencion'], 'default', 'value' => null],
            [['id_barrio', 'id_paciente', 'id_provincia', 'id_localidad', 'id_tipodom', 'id_registroatencion'], 'integer'],
            [['fecha_baja'], 'safe'],
            [['principal'], 'boolean'],
            [['id_barrio'], 'exist', 'skipOnError' => true, 'targetClass' => Barrio::className(), 'targetAttribute' => ['id_barrio' => 'id']],
            [['id_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => Localidad::className(), 'targetAttribute' => ['id_localidad' => 'id']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['id_paciente' => 'id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['id' => 'id']],
            [['id_registroatencion'], 'exist', 'skipOnError' => true, 'targetClass' => Registroatencion::className(), 'targetAttribute' => ['id_registroatencion' => 'id']],
 		           [['id_provincia'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::className(), 'targetAttribute' => ['id_provincia' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'direccion' => 'Direccion',
            'id_barrio' => 'Id Barrio',
            'id_paciente' => 'Id Paciente',
            'id_provincia' => 'Id Provincia',
            'id_localidad' => 'Id Localidad',
            'id_tipodom' => 'Id Tipodom',
            'fecha_baja' => 'Fecha Baja',
            'id_registroatencion' => 'Id Registroatencion',
             'principal' => 'Principal',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroatencion()
    {
        return $this->hasOne(Registroatencion::className(), ['id' => 'id_registroatencion']);
    }
    /**
       * @return \yii\db\ActiveQuery
       */
      public function getBarrio()
      {
          return $this->hasOne(Barrio::className(), ['id' => 'id_barrio']);
      }

      /**
       * @return \yii\db\ActiveQuery
       */
      public function getLocalidad()
      {
          return $this->hasOne(Localidad::className(), ['id' => 'id_localidad']);
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
 		   public function getProvincia()
 		   {
 		       return $this->hasOne(Provincia::className(), ['id' => 'id_provincia']);
 		   }

}
