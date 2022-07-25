<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "anionota".
 *
 * @property int $id
 * @property int $anio
 * @property bool $activo
 * @property Registroatencion[] $registroatencions
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Anionota extends \yii\db\ActiveRecord
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
        return 'anionota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anio'], 'default', 'value' => null],
            [['anio'], 'integer'],
            [['activo'], 'boolean'],
            [['anio'], 'unique'],
        ];
    }
    public function actualizarEstado()  {
        $db = Yii::$app->db;
        $db->createCommand('UPDATE anionota SET activo = false')->execute();;

    }
    public function getAnionotaActivo($fecha) {
        $fechaEntera = strtotime($fecha);
        $anio = date("Y", $fechaEntera);
        $cantidad= Anionota::find()->andWhere(['and' ,"anio =" .$anio ." and activo=true" ])->count();
        if ($cantidad == 1){
          return true;
        }else {
          return false;
         }
    }
    public function anionotaActivo(){
      $anioNota = Anionota::find()->andWhere(['and' ,"activo=true" ])->one();
      return $anioNota ;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'anio' => 'Anio',
            'activo' => 'Activo',
        ];
    }
    /**
 * @return \yii\db\ActiveQuery
 */
    public function getRegistroatencions()
    {
        return $this->hasMany(Registroatencion::className(), ['id_anionota' => 'id']);
    }


}
