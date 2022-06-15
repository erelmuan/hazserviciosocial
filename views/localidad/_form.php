<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Provincia;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Localidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="localidad-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if(!$model->isNewRecord && $model->provincia->domicilios){  ?>
      <span style="color:red">No se puede modificar el nombre ni la provincia, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <!--si existen domicilio asociados no se puede modificar el nombre  -->
    <? if(!$model->isNewRecord && $model->provincia->domicilios){
          echo $form->field($model, 'id_provincia')->hiddenInput()->label(false);
          echo  $form->field($model, 'provincia')->input("text",['readonly' => true , "value"=>$model->provincia->nombre])->label('Provincia');
          }else {
            echo $form->field($model, 'id_provincia')->dropDownList(ArrayHelper::map(Provincia::find()->all(), 'id','nombre'))->label('Provincia') ;
        }
    ?>

    <!--si existen domicilio asociados no se puede modificar el nombre  -->
    <? if(!$model->isNewRecord &&  $model->provincia->domicilios){
            echo  $form->field($model, 'nombre')->input("text",['readonly' => true])->label('Nombre');
          }else {
            echo  $form->field($model, 'nombre')->input("text",['style'=> 'width:100%; text-transform:uppercase;'])->label('Nombre');
        }
    ?>

    <?= $form->field($model, 'codigopostal')->textInput() ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
