<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Organismo;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Area */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-form">

    <?php $form = ActiveForm::begin(); ?>
    <? if($model->registroatencions){    ?>
      <span style="color:red">No se puede modificar, debido a que hay una asociación a uno o más registros</span>
    <? } ?>
    <?= $form->field($model, 'nombre')->textInput(['disabled'=>(count($model->registroatencions) >0)]) ?>

    <?  echo $form->field($model, 'id_organismo')->dropDownList(ArrayHelper::map(Organismo::find()->orderBy(['organismo.nombre'=>SORT_ASC])->all(), 'id','nombre'))->label('Organismo') ; ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar',['disabled'=>(count($model->registroatencions) >0), 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
