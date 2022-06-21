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
 */
class Area extends \yii\db\ActiveRecord
{
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
            [['nombre', 'id_organismo'], 'default', 'value' => null],
            [['nombre', 'id_organismo'], 'integer'],
            [['id_organismo'], 'required'],
            [['id_organismo'], 'unique'],
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
}
