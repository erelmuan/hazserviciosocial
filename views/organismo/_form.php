<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Organismo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organismo-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if($model->registroatencions){    ?>
      <span style="color:red">No se puede modificar el nombre, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <?= $form->field($model, 'nombre')->input("text",['disabled' =>(count ($model->registroatencions)>0)]) ?>

    <?= $form->field($model, 'direccion')->textInput() ?>

    <?= $form->field($model, 'telefono')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
