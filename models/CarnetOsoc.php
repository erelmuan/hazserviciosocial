<?php

namespace app\models;
use yii\helpers\ArrayHelper;

use app\components\behaviors\AuditoriaBehaviors;

use Yii;

/**
 * This is the model class for table "carnet_osoc".
 *
 * @property int $id
 * @property int $id_paciente
 * @property int $id_obrasocial
 * @property string $nroafiliado
 * @property string $fecha_baja
 * @property Paciente $paciente
 * @property Obrasocial $obrasocial
 */
class CarnetOsoc extends \yii\db\ActiveRecord
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
        return 'carnet_osoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_paciente', 'id_obrasocial'], 'default', 'value' => null],
            [['id_paciente', 'id_obrasocial'], 'integer'],
            [['nroafiliado'], 'string'],
            [['fecha_baja'], 'safe'],
            [['id_obrasocial'], 'exist', 'skipOnError' => true, 'targetClass' => Obrasocial::className(), 'targetAttribute' => ['id_obrasocial' => 'id']],
            [['id_paciente'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::className(), 'targetAttribute' => ['id_paciente' => 'id']],
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
            'id_obrasocial' => 'Id Obrasocial',
            'nroafiliado' => 'Nroafiliado',
             'fecha_baja' => 'Fecha Baja',
        ];
    }

    // /**
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getObrasocial()
    // {
    //     return $this->hasOne(Obrasocial::className(), ['id' => 'id_obrasocial']);
    // }

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
    public function getObrasocial()
    {
       return $this->hasOne(Obrasocial::className(), ['id' => 'id_obrasocial']);
    }
 
    public function getObrasociales()
    {
      return ArrayHelper::map(Obrasocial::find()->all(), 'id','denominacion');
    }
}
