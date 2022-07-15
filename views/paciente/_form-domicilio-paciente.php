<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Provincia;
use app\models\Localidad;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use app\models\Barrio;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
?>

   <div class="row domicilio">
     <div class="col-lg-2">
       <?= $form->field($domicilio, 'direccion')->textInput([
       'id' => "domicilio-direccion{$key}",
       'name' => "Domicilios[$key][direccion]",
        'class' =>'form-control'
     ])->label(false) ?>
     </div>
     <div class="col-lg-1">
       <?= $form->field($domicilio, 'id_tipodom')->dropDownList(
         $domicilio->getTipodoms(),
       ['id' => "domicilio-id_tipo{$key}",
           'name' => "Domicilios[$key][id_tipodom]",
          ]
         )->label(false) ;?>
     </div>
       <div class="col-lg-2">
         <?php
          echo $form->field($domicilio, 'id_provincia')->dropDownList(
              $domicilio->getProvincias(),
              [
                  'id' => "domicilio-provincia{$key}",
                  'name' => "Domicilios[$key][id_provincia]",
                  'prompt'=>'Por favor elija una',
                  'onchange'=>'$.get( "'.Url::toRoute('localidad/arraylocalidades').'", { id: $(this).val() } )
                              .done(function( data ) {
                          $( "#'.Html::getInputId($domicilio, "localidad{$key}").'" ).html( data );
                        }
                    );'
              ]
          )->label(false);?>

      </div>
   		<div class="col-lg-2">
        <?php echo $form->field($domicilio, 'id_localidad')->dropDownList(
          ($domicilio->isNewRecord)?array() : $domicilio->getLocalidades($domicilio->provincia->id),
            [
               'id' => "domicilio-localidad{$key}",
               'name' => "Domicilios[$key][id_localidad]",
                'prompt'=>'Por favor elija uno',
                'onchange'=>'$.get( "'.Url::toRoute('barrio/arraybarrios').'", { id: $(this).val() } )
                                    .done(function( data ) {
                                      $( "#'.Html::getInputId($domicilio, "barrio{$key}").'" ).html( data );
                              }
                          );'
            ]

        )->label(false); ?>

		</div>
    <div class="col-lg-2">
    <?  if ($domicilio->isNewRecord)
          echo $form->field($domicilio, 'id_barrio')->dropDownList(
            ($domicilio->isNewRecord)?array() : $domicilio->getBarrios($domicilio->localidad->id),
            ['prompt'=>'Por favor elija una',
          'id' => "domicilio-barrio{$key}",
          'name' => "Domicilios[$key][id_barrio]",
          ])->label(false);
      else
      {
          $barrios = ArrayHelper::map(Barrio::find()->where(['id_localidad' =>$domicilio->id_localidad])->all(), 'id', 'nombre');
          echo $form->field($domicilio, 'id_barrio')->dropDownList($barrios, [ 'id' => "domicilio-barrio{$key}",
            'name' => "Domicilios[$key][id_barrio]"])->label(false);
      }

      ?>


		</div>
    <div class="col-lg-2">
    <?=$form->field($domicilio, 'fecha_baja')
      ->widget(DateControl::classname(), [
        'options' => ['placeholder' => 'Debe agregar una fecha',
                ],
        'type'=>DateControl::FORMAT_DATE,
        'displayFormat' => 'php:d/m/Y',
        'saveFormat' => 'php:Y-m-d',
        'saveOptions' => [
          'name' => "Domicilios[$key][fecha_baja]",
          'id' => "domicilio-fecha_baja{$key}",
              ]
          ])->label(false);
  ?>
    </div>

    	<div class="col-lg-1">
         <?= Html::a('<i class="glyphicon glyphicon-trash" ></i> ' , 'javascript:void(0);', [
           'class' => 'paciente-eliminar-domicilio-boton btn btn-danger',
           'title'=>'Eliminar'
            ]) ?>
    	</div>
    </div>
