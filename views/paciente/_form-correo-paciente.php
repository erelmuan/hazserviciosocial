<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
?>
   <div class="row correo">
     <div class="col-lg-3">

     <?=$form->field($correo, 'direccion')->widget(\yii\widgets\MaskedInput::className(), [
     'clientOptions' => [
      'name' => 'input-5',
      'alias' =>  ['email'],
     ],
    'options' => [
      'id' => "correo-numero{$key}",
      'name' => "Correos[$key][direccion]",
       ],
   ])->label(false);?>
     </div>

    <div class="col-lg-2">
      <?=$form->field($correo, 'fecha_baja')
    ->widget(DateControl::classname(), [
      'options' => ['placeholder' => 'Debe agregar una fecha',
              ],
      'type'=>DateControl::FORMAT_DATE,
      'displayFormat' => 'php:d/m/Y',
      'saveFormat' => 'php:Y-m-d',
      'saveOptions' => [
        'name' => "Correos[$key][fecha_baja]",
        'id' => "correo-fecha_baja{$key}",
            ],
        ])->label(false);
    ?>

    </div>

    	<div class="col-lg-1">
         <?= Html::a('<i class="glyphicon glyphicon-trash" ></i> ' , ($correo->isNewRecord)?'javascript:void(0);':'', [
           'class' => 'paciente-eliminar-correo-boton btn btn-danger',
           'title'=>($correo->isNewRecord)?'Eliminar':'NO SE PUEDE ELIMINAR',
           'disabled'=> ($correo->isNewRecord)?false:true

            ]) ?>
    	</div>
    </div>
