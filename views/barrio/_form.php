<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Localidad;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Barrio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barrio-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if($model->domicilios){    ?>
      <span style="color:red">No se puede modificar, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <?= $form->field($model, 'nombre')->textInput(['readOnly'=>(count($model->domicilios) >0)]) ?>

    <?  echo $form->field($model, 'id_localidad')->dropDownList(ArrayHelper::map(Localidad::find()->all(), 'id','nombre'),['readOnly'=>(count($model->domicilios) >0)])->label('Localidad') ; ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['disabled'=>(count($model->domicilios) >0) ,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
