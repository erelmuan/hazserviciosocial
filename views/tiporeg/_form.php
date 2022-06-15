<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tiporeg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tiporeg-form">
  <? if($model->registroatencions){    ?>
    <span style="color:red">No se puede modificar, debido a que hay una asociación a uno o más registros</span>
  <? } ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->input("text",['disabled' =>(count ($model->registroatencions)>0)])?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['disabled'=>(count($model->registroatencions) >0),'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
