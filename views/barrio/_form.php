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

    <?= $form->field($model, 'nombre')->textInput() ?>

    <?  echo $form->field($model, 'id_localidad')->dropDownList(ArrayHelper::map(Localidad::find()->all(), 'id','nombre'))->label('Localidad') ; ?>


	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
