<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tiporeg".
 *
 * @property int $id
 * @property string $descripcion
 *
 * @property Registroatencion[] $registroatencions
 */
 use app\components\behaviors\AuditoriaBehaviors;

class Tiporeg extends \yii\db\ActiveRecord
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
        return 'tiporeg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
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
    public function getRegistroatencions()
    {
        return $this->hasMany(Registroatencion::className(), ['id_tiporeg' => 'id']);
    }
}
