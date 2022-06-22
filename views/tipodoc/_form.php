<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tipodoc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tipodoc-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if(!$model->isNewRecord && $model->pacientes){  ?>
      <span style="color:red">No se puede modificar el documento, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <!--si existen pacientes asociados no se puede modificar el nombre  -->
    <?=$form->field($model, 'documento')->input("text",['disabled'=>(count($model->pacientes) >0),'style'=> 'width:100%; text-transform:uppercase;'])->label('Documento');

    ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar',['disabled'=>(count($model->pacientes) >0), 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
