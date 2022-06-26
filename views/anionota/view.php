<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Anionota */
?>
<div class="anionota-view">
  <div class="anionota-view">
      <div id="w0s" class="x_panel">
        <div class="x_title"><h2><i class="fa fa-table"></i> Año - nota  </h2>
          <div class="clearfix"> <div class="nav navbar-right panel_toolbox"><?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Ir a Año - nota', ['/anionota/index'], ['class'=>'btn btn-danger grid-button']) ?></div>
      </div>
        </div>
  <?= $this->render('_detalleview', [
      'model' => $model,
  ]) ?>
</div>
</div>
</div>
