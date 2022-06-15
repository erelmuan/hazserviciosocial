<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo".
 *
 * @property int $id
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property string $email
 *
 * @property Registroatencion[] $registroatencions
 */
class Organismo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organismo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre', 'direccion', 'telefono', 'email'], 'string'],
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
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroatencions()
    {
        return $this->hasMany(Registroatencion::className(), ['id_organismo' => 'id']);
    }
}
