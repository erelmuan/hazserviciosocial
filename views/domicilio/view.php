<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Domicilio */
?>
<div class="domicilio-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'direccion',
            'id_barrio',
            'id_paciente',
            'id_provincia',
            'id_localidad',
            'id_tipodom',
            'fecha_baja',
        ],
    ]) ?>

</div>
