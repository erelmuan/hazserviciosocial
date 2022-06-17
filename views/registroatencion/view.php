<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Registroatencion */
?>
<div class="registroatencion-view">
  <div class="biopsia-view">
      <div id="w0s" class="x_panel">
        <div class="x_title"><h2><i class="fa fa-table"></i> REGISTRO DE ATENCIÓN  </h2>
          <div class="clearfix"> <div class="nav navbar-right panel_toolbox"><?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Ir a Registros', ['/registroatencion/index'], ['class'=>'btn btn-danger grid-button']) ?></div>
      </div>
        </div>
        <?= $this->render('_detalleview', [
            'model' => $model,
        ]) ?>

</div>
</div>
</div>
