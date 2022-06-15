<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Registroatencion */
?>
<div class="registroatencion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
              'value'=> $model->paciente->apellido .' '.$model->paciente->nombre,
              'label'=> 'Paciente',
            ],
            'motivo',
            [
              'value'=> $model->tiporeg->descripcion,
              'label'=> 'Tipo de registro',
            ],
            [
              'value'=> $model->organismo->nombre,
              'label'=> 'Organismo',
            ],
            [
            'label'=> 'Fecha',
            'value'=> $model->fecha ,
            'format' => ['date', 'd/M/Y'],
              ],
            'numero_nota',
            [
              'value'=> $model->usuario->nombre,
              'label'=> 'Usuario',
            ],
        ],
    ]) ?>

</div>
