<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $id
 * @property int $nombre
 * @property int $id_organismo
 *
 * @property Organismo $organismo
 * @property Registroatencion[] $registroatencions

 */
 use app\components\behaviors\AuditoriaBehaviors;

class Area extends \yii\db\ActiveRecord
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
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_organismo'], 'default', 'value' => null],
            [['nombre'], 'string'],
            [['id_organismo'], 'integer'],
            [['id_organismo'], 'required'],
            [['id_organismo'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['id_organismo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'id_organismo' => 'Id Organismo',
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
  public function getRegistroatencions()
  {
      return $this->hasMany(Registroatencion::className(), ['id_area' => 'id']);
  }
}
