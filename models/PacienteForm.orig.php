<?php
namespace app\models;
use app\models\Paciente;
use app\models\Domicilio;
use app\models\Telefono;
use app\models\Correo;
use app\models\CarnetOs;
use Yii;
use yii\base\Model;
use yii\widgets\ActiveForm;

class PacienteForm extends Model
{
    private $_paciente; //Atributo donde se guardará el paciente
    private $_domicilios; //Atributo donde se guardará la lista de domicilios
    private $_telefonos; //Atributo donde se guardará la lista de telefonos
    private $_correos; //Atributo donde se guardará la lista de correos
    private $_carnets; //Atributo donde se guardará la lista de carnets de obras sociales

    public function rules()
    {
        return [
            [['Paciente'], 'required'],
            [['Domicilios'], 'safe'],
        ];
    }


    public function save()
    {
      //Validar paciente
       if(!$this->paciente->validate()) {
            return false;
        }
        //Iniciar transacción
        $transaction = Yii::$app->db->beginTransaction();
        //Guardar paciente
        if (!$this->paciente->save()) {
            $transaction->rollBack();
            return false;
        }
        //Guardar lista de domicilios
        if (!$this->saveDomicilios()) {
            $transaction->rollBack();
            return false;
        }
        //Finalizar transacción
        $transaction->commit();
        return true;
    }

    public function savedomicilios()
    {
        //Arreglo con los domicilios que ya estan en la db y deben mantenerse
        //Al actualizar el paciente actualiza los domicilios que han sido modificado y elimina aquellos que han sido removidos
        $mantener = [];
        //Recorrer los domicilios
        foreach ($this->domicilios as $domicilio) {
            //Asignar el id de paciente
            $domicilio->id_paciente = $this->paciente->id;
            //Guardar domicilio
            if (!$domicilio->save()) {
              return false;
            }
            //Agregar id del domicilio a lista
            $mantener[] = $domicilio->id;
        }
        //Buscar todos los domicilios asociados a la paciente
        $query = Domicilio::find()->andWhere(['id_paciente' => $this->paciente->id]);
        if ($mantener) {
            //Filtrar por los domicilios que no estan en la lista de mantener
            $query->andWhere(['not in', 'id', $mantener]);
        }
        //Eliminar los domicilios que no estan en la lista
        foreach ($query->all() as $domicilio) {
            $domicilio->delete();
        }
        return true;
    }

    public function delete()
    {
        //Iniciar transacción
        $transaction = Yii::$app->db->beginTransaction();
        //Eliminar domicilios
        if (!$this->deletedomicilios()) {
            $transaction->rollBack();
            return false;
        }
        //Eliminar paciente
        if (!$this->paciente->delete()) {
            $transaction->rollBack();
            return false;
        }
        //Finalizar transacción
        $transaction->commit();
        return true;
    }

    public function deletedomicilios()
    {
        //Recoorrer los domicilios
        foreach ($this->domicilios as $domicilio) {
          //Eliminar domicilio
           if (!$domicilio->delete()) {
                return false;
            }
        }
        return true;
    }

    public function getPaciente()
    {
        return $this->_paciente;
    }

    public function setPaciente($paciente)
    {
        if ($paciente instanceof Paciente) {
            $this->_paciente = $paciente;
        } else if (is_array($paciente)) {
            $this->_paciente->setAttributes($paciente);
        }
    }
// SECCION DOMICILIOS //
    public function getdomicilios()
    {
        if ($this->_domicilios=== null) {
            $this->_domicilios = $this->paciente->isNewRecord ? [] : $this->paciente->domicilios;
        }
        return $this->_domicilios;
    }

    private function getDomicilio($key)
    {
        $domicilio = $key && strpos($key, 'nuevo') === false ? Domicilio::findOne($key) : false;
        if (!$domicilio) {
            $domicilio = new Domicilio();
            $domicilio->loadDefaultValues();
        }
        return $domicilio;
    }

    public function setDomicilios($domicilios)
    {
        unset($domicilios['__id__']); // Elimina el domicilio vacío usado para crear formularios
        $this->_domicilios = [];
        //Recorrer domicilios
        foreach ($domicilios as $key => $domicilio) {
          //Obtiene domicilio por clave y lo agrega al atributo domicilios
            if (is_array($domicilio)) {
                $this->_domicilios[$key] = $this->getDomicilio($key);
                $this->_domicilios[$key]->setAttributes($domicilio);
            } elseif ($domicilios instanceof Domicilio) {
                $this->_domicilios[$domicilio->id] = $domicilio;
            }
        }
    }
    // SECCION TELEFONOS //
    public function getEntidades($entidades)
    {
        $entidad=str_replace(array("_"), '', $entidades);
        if ($this->$entidades=== null) {
            $this->$entidades = $this->paciente->isNewRecord ? [] : $this->paciente->$entidad;
        }
        return $this->$entidades;
    }

    private function getTelefono($key)
    {
        $telefono = $key && strpos($key, 'nuevo') === false ? Telefono::findOne($key) : false;
        if (!$telefono) {
            $telefono = new Telefono();
            $telefono->loadDefaultValues();
        }
        return $telefono;
    }

    public function setTelefonos($telefonos)
    {
        unset($telefonos['__id__']); // Elimina el telefono vacío usado para crear formularios
        $this->_telefonos = [];
        //Recorrer telefonos
        foreach ($telefonos as $key => $telefono) {
          //Obtiene telefono por clave y lo agrega al atributo telefonos
            if (is_array($telefono)) {
                $this->_telefonos[$key] = $this->getTelefono($key);
                $this->_telefonos[$key]->setAttributes($telefono);
            } elseif ($telefonos instanceof Telefono) {
                $this->_telefonos[$telefono->id] = $telefono;
            }
        }
    }

}
