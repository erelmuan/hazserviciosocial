<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "obrasocial".
 *
 * @property int $id
 * @property string $sigla
 * @property string $denominacion
 * @property string $direccion
 * @property int $id_localidad
 * @property string $paginaweb
 * @property string $observaciones
 * @property string $correoelectronico
 * @property string $telefono
 * @property CarnetOsoc[] $CarnetOsoc
 		* @property Localidad $localidad
 		* @property Provincia $provincia
 		* @property Paciente[] $pacientes
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Obrasocial extends \yii\db\ActiveRecord
{
  public function behaviors()
  {

    return array(
           'AuditoriaBehaviors'=>array(
                  'class'=>AuditoriaBehaviors::className(),
                  ),
      );
 }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'obrasocial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono', 'id_localidad'], 'default', 'value' => null],
            [['telefono', 'id_localidad'], 'integer'],
            [['observaciones', 'correoelectronico'], 'string'],
            [['sigla'], 'string', 'max' => 15],
            [['denominacion'], 'string', 'max' => 60],
            [['direccion'], 'string', 'max' => 70],
            [['paginaweb'], 'string', 'max' => 35],
            [['id_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => Localidad::className(), 'targetAttribute' => ['id_localidad' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'sigla' => 'Sigla',
            'denominacion' => 'Denominacion',
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
            'id_localidad' => 'Id_localidad',
            'paginaweb' => 'Pagina web',
            'observaciones' => 'Observaciones',
            'correoelectronico' => 'Correo electronico',
        ];
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
 		   public function getCarnetOsocs()
 		   {
 		       return $this->hasMany(CarnetOsoc::className(), ['id_obrasocial' => 'id']);
 		   }

    /**
		    * @return \yii\db\ActiveQuery
		    */
		   public function getCarnetOsoc()
		   {
		       return $this->hasMany(CarnetOsoc::className(), ['id_obrasocial' => 'id']);
		   }

}
