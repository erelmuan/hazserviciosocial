<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidad".
 *
 * @property int $id
 * @property int $id_provincia
 * @property string $nombre
 * @property int $codigopostal
 *
 * @property Barrio[] $barrios
 * @property Domicilio[] $domicilios
 * @property Provincia $provincia
 * @property Obrasocial[] $obrasocials
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Localidad extends \yii\db\ActiveRecord
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
        return 'localidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_provincia', 'codigopostal'], 'default', 'value' => null],
            [['id_provincia', 'codigopostal'], 'integer'],
            [['nombre'], 'string', 'max' => 65],
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
            'id_provincia' => 'Id Provincia',
            'nombre' => 'Nombre',
            'codigopostal' => 'Codigopostal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarrios()
    {
        return $this->hasMany(Barrio::className(), ['id_localidad' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicilios()
    {
        return $this->hasMany(Domicilio::className(), ['id_localidad' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvincia()
    {
        return $this->hasOne(Provincia::className(), ['id' => 'id_provincia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObrasocials()
    {
        return $this->hasMany(Obrasocial::className(), ['id_localidad' => 'id']);
    }
}
