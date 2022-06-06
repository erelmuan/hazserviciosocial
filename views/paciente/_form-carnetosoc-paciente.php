<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
?>
   <div class="row carnetosoc">
     <div class="col-lg-2">
       <?= $form->field($carnetOsoc, 'id_obrasocial')->dropDownList(
         $carnetOsoc->getObrasociales(),
       ['id' => "carnetosoc-id_obrasocial{$key}",
           'name' => "CarnetOsocs[$key][id_obrasocial]",
          ]
         )->label(false) ;?>
     </div>
     <div class="col-lg-2">
       <?= $form->field($carnetOsoc, 'nroafiliado')->textInput(
     [ 'id' => "carnetosoc-nroafiliado{$key}",
       'name' => "CarnetOsocs[$key][nroafiliado]",
     ])->label(false) ?>
     </div>
     <div class="col-lg-2">

     <?=$form->field($carnetOsoc, 'fecha_baja')
       ->widget(DateControl::classname(), [
         'options' => ['placeholder' => 'Debe agregar una fecha',
                 ],
         'type'=>DateControl::FORMAT_DATE,
         'displayFormat' => 'php:d/m/Y',
         'saveFormat' => 'php:Y-m-d',
         'saveOptions' => [
           'name' => "CarnetOsocs[$key][fecha_baja]",
           'id' => "carnetosoc-fecha_baja{$key}",
               ],
           ])->label(false);
   ?>
     </div>

    	<div class="col-lg-1">
         <?= Html::a('<i class="glyphicon glyphicon-trash" ></i> ' , 'javascript:void(0);', [
           'class' => 'paciente-eliminar-carnetosoc-boton btn btn-danger',
           'title'=>'Eliminar'
            ]) ?>
    	</div>
    </div>
