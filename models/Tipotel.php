<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipotel".
 *
 * @property int $id
 * @property string $descripcion
 *
 * @property Telefono $telefono
 */
class Tipotel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipotel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefono()
    {
        return $this->hasOne(Telefono::className(), ['id' => 'id']);
    }
}
