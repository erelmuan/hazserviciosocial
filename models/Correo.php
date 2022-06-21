<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "correo".
 *
 * @property int $id
 * @property string $direccion
 * @property int $id_paciente
 * @property string $fecha_baja
 *
 * @property Paciente $paciente
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Correo extends \yii\db\ActiveRecord
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
        return 'correo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['direccion'], 'required'],
            [['direccion'], 'string'],
            [['id_paciente'], 'default', 'value' => null],
            [['id_paciente'], 'integer'],
            [['fecha_baja'], 'safe'],
            [['direccion'], 'unique'],
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
            'direccion' => 'Direccion',
            'id_paciente' => 'Id Paciente',
            'fecha_baja' => 'Fecha Baja',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::className(), ['id' => 'id_paciente']);
    }
}
