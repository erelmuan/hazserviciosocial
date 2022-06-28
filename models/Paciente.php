<?php

namespace app\models;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "paciente".
 *
 * @property string $nombre
 * @property string $sexo
 * @property string $fecha_nacimiento
 * @property string $apellido
 * @property string $hc
 * @property int $id_nacionalidad
 * @property int $id_tipodoc
 * @property int $id
 * @property string $num_documento
 *
 * @property CarnetOsoc[] $carnetOsocs
 * @property Correo[] $correos
 * @property Domicilio[] $domicilios
 * @property Nacionalidad $nacionalidad
 * @property Tipodoc $tipodoc
* @property Registroatencion[] $registroatencions
 * @property Solicitudbiopsia[] $solicitudbiopsias
 * @property Solicitudpap[] $solicitudpaps
 * @property Telefono[] $telefonos
 */
class Paciente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paciente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'sexo', 'fecha_nacimiento', 'apellido'], 'required'],
            [['sexo', 'hc', 'num_documento'], 'string'],
            [['fecha_nacimiento'], 'safe'],
            [['id_nacionalidad', 'id_tipodoc'], 'default', 'value' => null],
            [['id_nacionalidad', 'id_tipodoc'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['apellido'], 'string', 'max' => 60],
            [['id_nacionalidad', 'id_tipodoc', 'id_localidad', 'id_provincia'], 'default', 'value' => null],
            [['id_nacionalidad', 'id_tipodoc', 'id_localidad', 'id_provincia'], 'integer'],
            [['id_tipodoc', 'num_documento'], 'unique', 'targetAttribute' => ['id_tipodoc', 'num_documento']],
            [['id_nacionalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Nacionalidad::className(), 'targetAttribute' => ['id_nacionalidad' => 'id']],
            [['id_tipodoc'], 'exist', 'skipOnError' => true, 'targetClass' => Tipodoc::className(), 'targetAttribute' => ['id_tipodoc' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'sexo' => 'Sexo',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'apellido' => 'Apellido',
            'hc' => 'Hc',
            'id_nacionalidad' => 'Id Nacionalidad',
            'id_tipodoc' => 'Id Tipodoc',
            'id' => 'ID',
            'num_documento' => 'Num Documento',
            'id_localidad' => 'Id Localidad',
            'id_provincia' => 'Id Provincia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   public function getCarnetOsocs()
     {
       return $this->hasMany(CarnetOsoc::className(), ['id_paciente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorreos()
    {
        return $this->hasMany(Correo::className(), ['id_paciente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicilios()
    {
        return $this->hasMany(Domicilio::className(), ['id_paciente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNacionalidad()
    {
        return $this->hasOne(Nacionalidad::className(), ['id' => 'id_nacionalidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipodoc()
    {
        return $this->hasOne(Tipodoc::className(), ['id' => 'id_tipodoc']);
    }
    public function getTipodocs() {
            return ArrayHelper::map(Tipodoc::find()->all(), 'id','documento');

        }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefonos()
    {
        return $this->hasMany(Telefono::className(), ['id_paciente' => 'id']);
    }
    public function getNacionalidades()
    {
        return ArrayHelper::map(Nacionalidad::find()->all(), 'id','gentilicio');
    }
    /**
       * @return \yii\db\ActiveQuery
       */
      public function getRegistroatencions()
      {
          return $this->hasMany(Registroatencion::className(), ['id_paciente' => 'id']);
      }
    public function getDomicilioprincipal()
    {
      //CORREGIR DEVOLVER SOLO AQUELLOS DOMICILIO MARCADOS COMO PRINCIPAL
       return $this->hasOne(Domicilio::className(), ['id_paciente' => 'id'])->where(['principal' => true]);
    }
}
