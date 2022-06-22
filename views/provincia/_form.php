<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Provincia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provincia-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if(!$model->isNewRecord && $model->domicilios){  ?>
      <span style="color:red">No se puede modificar el nombre, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <!--si existen pacientes asociados no se puede modificar el nombre  -->
    <?=$form->field($model, 'nombre')->input("text",['disabled'=>(count($model->domicilios) >0) ,'style'=> 'width:100%; text-transform:uppercase;'])->label('Nombre');  ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
