<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Correo */
?>
<div class="correo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'direccion',
            'id_paciente',
            'fecha_baja',
        ],
    ]) ?>

</div>
