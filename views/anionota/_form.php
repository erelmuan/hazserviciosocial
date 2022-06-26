<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Anionota */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="w0" class="x_panel">
  <div class="clearfix"> <div class="nav navbar-right panel_toolbox"><?= $model->isNewRecord ? "" :Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Atrás', ['/anionota/index'], ['class'=>'btn btn-danger grid-button']) ?></div>

<div class="anionota-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'anio')->textInput() ?>

    <?= $form->field($model, 'activo')->checkbox() ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
