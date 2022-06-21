<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Area */
?>
<div class="area-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'id_organismo',
        ],
    ]) ?>

</div>
