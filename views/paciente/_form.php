 <?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\bootstrap\ActiveForm; //used to enable bootstrap layout options
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\models\Provincia;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use app\models\Domicilio;
use app\models\Telefono;
use app\models\Correo;
use app\models\CarnetOsoc;

?>


<!-- <div id="w0" class="x_panel"> -->
<div  class="x_panel">

    <div class="x_title">
        <h2> <?=$model->paciente->isNewRecord ? "<i class='glyphicon glyphicon-plus'></i> NUEVO PACIENTE" : "<i class='glyphicon glyphicon-pencil'></i> ACTUALIZAR PACIENTE" ; ?>
        </h2>
        <div class="clearfix">
            <div class="nav navbar-right panel_toolbox">
                <?=Html::button('<i class="glyphicon glyphicon-arrow-left"></i> Atrás',array('name' => 'btnBack','onclick'=>'js:history.go(-1);returnFalse;','id'=>'botonAtras')); ?>
        </div>
      </div>
<div class="paciente-form">
  <? if($model->paciente->registroatencions){ ?>
    <span style="color:red">  Advertencia: La modificacion del nombre, apellido, dni o historia clinica impactara en todos los registros anteriores del paciente <b>(NO CAMBIE LA IDENTIDAD DEL MISMO)</b>.</span>
  <? } ?>


    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
    <div class="form-row mt-4">
      <div class="col-sm-4 pb-3">
          <?= $form->field($model->paciente, 'nombre')->input("text",['style'=> 'width:100%; text-transform:uppercase;'])->label('Nombre');?>
      </div>
      <div class="col-sm-4 pb-3">
        <?= $form->field($model->paciente, 'apellido')->input("text",['style'=> 'width:100%; text-transform:uppercase;'])->label('Apellido');?>
      </div>
      <div class="col-sm-1 pb-4">
        <? echo $form->field($model->paciente, 'sexo')->dropDownList(
            ['F' => 'F ', 'M' => 'M']
            );
        ?>
     </div>
     <div class="col-sm-2 pb-4">
       <?= $form->field($model->paciente, 'id_nacionalidad')->dropDownList($model->paciente->getNacionalidades())->label('Nacionalidad') ;?>
     </div>
     </div>
  </div>
  <div class="row">
    <div class="form-row mt-4">
        <div class="col-sm-3 pb-5">
          <?=$form->field($model->paciente, 'id_tipodoc')->dropDownList($model->paciente->getTipodocs())->label('Tipo Doc.') ;  ?>
        </div>
        <div class="col-sm-2 pb-5">
          <?=$form->field($model->paciente, 'num_documento')->input("text",['style'=>'width:100%'])->label('N° doc.');  ?>
        </div>
        <div class="col-sm-2 pb-5">
          <?= $form->field($model->paciente, 'hc')->input("text",['style'=>'width:100%'])->label('H.C.') ?>
        </div>
       <div class="col-sm-3 pb-4">
         <? echo $form->field($model->paciente, 'fecha_nacimiento')->widget(DateControl::classname(), [
           'options' => ['placeholder' => 'Debe agregar una fecha',
             'value'=> ($model->paciente->fecha_nacimiento )?$model->paciente->fecha_nacimiento:'' ,
                   ],
           'type'=>DateControl::FORMAT_DATE,
           'autoWidget'=>true,
           'displayFormat' => 'php:d/m/Y',
           'saveFormat' => 'php:Y-m-d',
         ])->label('Fecha de nacimiento')

         ?>
      </div>

    </div>
</div>

  <!--Inicio sección de domicilios -->
  <?php
  //Cargar un domicilio por defecto
   $domicilio = new Domicilio();
   $domicilio->loadDefaultValues();

    ?>
   <div id="paciente-domicilios">
     <div class="row" style="margin-bottom:16px;">
       <div class="col-lg-12">
        <?php
        //Boton para insertar nuevo formulario de domicilio
        echo Html::a('<i class="glyphicon glyphicon-plus" ></i>', 'javascript:void(0);', [
           'id' => 'paciente-nuevo-domicilio-boton',
           'class' => 'btn btn-primary btn-md'
         ])
         ?>
         <b>NUEVO DOMICILIO</b>
    </div>
     </div>
    <!-- Cabeceras con etiquetas -->
    <div class="row">
      <div class="col-lg-2">
        <label class="control-label">
        Dirección
        </label>
      </div>
      <div class="col-lg-1">
        <label class="control-label">
        Tipo
        </label>
      </div>
      <div class="col-lg-2">
        <label class="control-label">
        Provincia
        </label>
      </div>
      <div class="col-lg-2">
        <label class="control-label">
        Localidad
        </label>
      </div>
      <div class="col-md-1">
        <label class="control-label">
        Barrio
        </label>
      </div>
      <div class="col-lg-1">
        <label class="control-label">
        Principal
        </label>
      </div>
      <div class="col-lg-2">
        <label class="control-label">
        Fecha de baja
        </label>
      </div>

      <div class="col-lg-1"></div>
    </div>
  </div>

  <?php
   //Recorrer los domicilios
    foreach ($model->domicilios as $key => $_domicilio) {
      //Para cada domicilio renderizar el formulario de domicilio
      //Si el domicilio está vacío colocar 'nuevo' como clave, si no asignar el id del domicilio
      echo '<tr>';
      echo $this->render('_form-domicilio-paciente', [
        'key' => $_domicilio->isNewRecord ? (strpos($key, 'nuevo') !== false ? $key : 'nuevo' . $key) : $_domicilio->id,
        'form' => $form,
        'domicilio' => $_domicilio,
      ]);
      echo '</tr>';
    }
  //domicilio vacío con su respectivo formulario que se utilizará para copiar cada vez que se presione el botón de nuevo domicilio
  $domicilio = new Domicilio();
  echo '<div id="paciente-nuevo-domicilio-block" style="display:none">';
  echo $this->render('_form-domicilio-paciente', [
        'key' => '__id__',
        'form' => $form,
        'domicilio' => $domicilio,
    ]);
    echo '</div>';
    ?>
  <!--Fin sección de domicilio -->

  <!--Inicio sección de telefono -->
    <?php
    //Cargar un telefono por defecto
     $telefono = new Telefono();
     $telefono->loadDefaultValues();
      ?>
     <div id="paciente-telefonos">
       <div class="row" style="margin-bottom:16px;">
         <div class="col-lg-12">
          <?php
          //Boton para insertar nuevo formulario de telgono
          echo Html::a('<i class="glyphicon glyphicon-plus" ></i>', 'javascript:void(0);', [
             'id' => 'paciente-nuevo-telefono-boton',
             'class' => 'btn btn-primary btn-md'
           ])
           ?>
      <b>NUEVO TELÉFONO</b>
      </div>
       </div>
      <!-- Cabeceras con etiquetas -->
      <div class="row">
        <div class="col-lg-2">
          <label class="control-label">
          Numero
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Empresa
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Tipo
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Fecha de baja
          </label>
        </div>
        <div class="col-lg-1"></div>
      </div>
    </div>
    <?php
     //Recorrer los telefonos
      foreach ($model->telefonos as $key => $_telefono) {
        //Para cada telefono renderizar el formulario de telefono
        //Si el telefono está vacío colocar 'nuevo' como clave, si no asignar el id del telefono
        echo '<tr>';
        echo $this->render('_form-telefono-paciente', [
          'key' => $_telefono->isNewRecord ? (strpos($key, 'nuevo') !== false ? $key : 'nuevo' . $key) : $_telefono->id,
          'form' => $form,
          'telefono' => $_telefono,
        ]);
        echo '</tr>';
      }
    //telefono vacío con su respectivo formulario que se utilizará para copiar cada vez que se presione el botón de nuevo domicilio
    $telefono = new Telefono();
    // $domicilio->loadDefaultValues();
    echo '<div id="paciente-nuevo-telefono-block" style="display:none">';
    echo $this->render('_form-telefono-paciente', [
          'key' => '__id__',
          'form' => $form,
          'telefono' => $telefono,
      ]);
      echo '</div>';
      ?>
    <!--Fin sección de telefono -->

    <!--Inicio sección de correos -->
    <?php
    //Cargar un correo por defecto
     $correo = new Correo();
     $correo->loadDefaultValues();
      ?>
     <div id="paciente-correos">
       <div class="row" style="margin-bottom:16px;">
         <div class="col-lg-12">
          <?php
          //Boton para insertar nuevo formulario de correo
          echo Html::a('<i class="glyphicon glyphicon-plus" ></i>', 'javascript:void(0);', [
             'id' => 'paciente-nuevo-correo-boton',
             'class' => 'btn btn-primary btn-md'
           ])
           ?>
       <b>NUEVO CORREO</b>

      </div>
       </div>
      <!-- Cabeceras con etiquetas -->
      <div class="row">
        <div class="col-lg-3">
          <label class="control-label">
          Correo
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Fecha de baja
          </label>
        </div>
        <div class="col-lg-1"></div>
      </div>
    </div>
    <?php
     //Recorrer los correos
      foreach ($model->correos as $key => $_correo) {
        //Para cada correo renderizar el formulario de correo
        //Si el correo está vacío colocar 'nuevo' como clave, si no asignar el id del correo
        echo '<tr>';
        echo $this->render('_form-correo-paciente', [
          'key' => $_correo->isNewRecord ? (strpos($key, 'nuevo') !== false ? $key : 'nuevo' . $key) : $_correo->id,
          'form' => $form,
          'correo' => $_correo,
        ]);
        echo '</tr>';
      }
    //telefono vacío con su respectivo formulario que se utilizará para copiar cada vez que se presione el botón de nuevo domicilio
    $telefono = new Correo();
    // $domicilio->loadDefaultValues();
    echo '<div id="paciente-nuevo-correo-block" style="display:none">';
    echo $this->render('_form-correo-paciente', [
          'key' => '__id__',
          'form' => $form,
          'correo' => $correo,
      ]);
      echo '</div>';
      ?>

    <!--Fin sección de correo -->


    <!--Inicio sección de obrasociales -->
    <?php
    //Cargar un correo por defecto
     $carnetOsoc = new CarnetOsoc();
     $carnetOsoc->loadDefaultValues();
      ?>
     <div id="paciente-carnetosoc">
       <div class="row" style="margin-bottom:16px;">
         <div class="col-lg-12">
          <?php
          //Boton para insertar nueva formulario obrasocial
          echo Html::a('<i class="glyphicon glyphicon-plus" ></i>', 'javascript:void(0);', [
             'id' => 'paciente-nuevo-carnetosoc-boton',
             'class' => 'btn btn-primary btn-md'
           ])
           ?>

      <b>NUEVA OBRA SOCIAL</b>
      </div>
       </div>
      <!-- Cabeceras con etiquetas -->
      <div class="row">
        <div class="col-lg-2">
          <label class="control-label">
          Obra social
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Numero de afiliado
          </label>
        </div>
        <div class="col-lg-2">
          <label class="control-label">
          Fecha de baja
          </label>
        </div>
        <div class="col-lg-1"></div>
      </div>
    </div>
    <?php
     //Recorrer las carnets de obras sociales
      foreach ($model->carnetOsocs as $key => $_carnetOsoc) {
        //Para cada correo renderizar el formulario de correo
        //Si el correo está vacío colocar 'nuevo' como clave, si no asignar el id del correo
        echo '<tr>';
        echo $this->render('_form-carnetosoc-paciente', [
          'key' => $_carnetOsoc->isNewRecord ? (strpos($key, 'nuevo') !== false ? $key : 'nuevo' . $key) : $_carnetOsoc->id,
          'form' => $form,
          'carnetOsoc' => $_carnetOsoc,
        ]);
        echo '</tr>';
      }
    //carnet obra social vacío con su respectivo formulario que se utilizará para copiar cada vez que se presione el botón de nuevo domicilio
    $carnetOsoc = new CarnetOsoc();
    echo '<div id="paciente-nuevo-carnetosoc-block" style="display:none">';
    echo $this->render('_form-carnetosoc-paciente', [
          'key' => '__id__',
          'form' => $form,
          'carnetOsoc' => $carnetOsoc,
      ]);
      echo '</div>';
      ?>

    <?php ob_start(); ?>

    <script>
         //Crear la clave para el domicilio
         var domicilio_k = <?php echo isset($key) ? str_replace('nuevo', '', $key) : 0; ?>;
         //Al hacer click en el boton de nuevo domicilio aumentar en uno la clave
         // y agregar un formulario de domicilio reemplazando la clave __id__ por la nueva clave
         $('#paciente-nuevo-domicilio-boton').on('click', function () {
             domicilio_k += 1;
             $('#paciente-domicilios').append($('#paciente-nuevo-domicilio-block').html().replace(/__id__/g, 'nuevo' + domicilio_k));
           });
        //Al hacer click en un botón de eliminar eliminar la fila más cercana
        $(document).on('click', '.paciente-eliminar-domicilio-boton', function () {
             $(this).closest('.row').remove();
         });
/////////////////////////////////////////////////////////////////////////////////////////
      //Crear la clave para el telefono
      var telefono_k = <?php echo isset($key) ? str_replace('nuevo', '', $key) : 0; ?>;
      //Al hacer click en el boton de nuevo telefono aumentar en uno la clave
      // y agregar un formulario de telefono reemplazando la clave __id__ por la nueva clave
      $('#paciente-nuevo-telefono-boton').on('click', function () {
          telefono_k += 1;
          $('#paciente-telefonos').append($('#paciente-nuevo-telefono-block').html().replace(/__id__/g, 'nuevo' + telefono_k));
        });
      //Al hacer click en un botón de eliminar eliminar la fila más cercana
      $(document).on('click', '.paciente-eliminar-telefono-boton', function () {
          $(this).closest('.row').remove();
      });
/////////////////////////////////////////////////////////////////////////////////
    //Crear la clave para el correo
    var correo_k = <?php echo isset($key) ? str_replace('nuevo', '', $key) : 0; ?>;
    //Al hacer click en el boton de nuevo correo aumentar en uno la clave
    // y agregar un formulario de correo reemplazando la clave __id__ por la nueva clave
    $('#paciente-nuevo-correo-boton').on('click', function () {
        correo_k += 1;
        $('#paciente-correos').append($('#paciente-nuevo-correo-block').html().replace(/__id__/g, 'nuevo' + correo_k));
      });
    //Al hacer click en un botón de eliminar eliminar la fila más cercana
    $(document).on('click', '.paciente-eliminar-correo-boton', function () {
        $(this).closest('.row').remove();
    });
/////////////////////////////////////////////////////////////////////////////////
    //Crear la clave para el carnet obra social
    var carnetOsoc_k = <?php echo isset($key) ? str_replace('nuevo', '', $key) : 0; ?>;
    //Al hacer click en el boton de nuevo carnet obra social aumentar en uno la clave
    // y agregar un formulario de carnet obra social reemplazando la clave __id__ por la nueva clave
    $('#paciente-nuevo-carnetosoc-boton').on('click', function () {
        carnetOsoc_k += 1;
        $('#paciente-carnetosoc').append($('#paciente-nuevo-carnetosoc-block').html().replace(/__id__/g, 'nuevo' + carnetOsoc_k));
      });
    //Al hacer click en un botón de eliminar eliminar la fila más cercana
    $(document).on('click', '.paciente-eliminar-carnetosoc-boton', function () {
        $(this).closest('.row').remove();
    });

     </script>
     <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->paciente->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->paciente->isNewRecord ? 'btn btn-success' : 'btn btn-primary','value'=>0,'name' => 'registro']) ?>
          <?= Html::submitButton($model->paciente->isNewRecord ? 'Crear y Cargar Registro' : 'Actualizar y Cargar Registro', ['class' => $model->paciente->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'value'=>1,'name' => 'registro']) ?>

      </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
