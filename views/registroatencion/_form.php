<? use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\form\ActiveField;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Organismo;
use app\models\Tiporeg;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;
use nex\chosen\Chosen;
use app\models\Usuario;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistroatencionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Registro de atención';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="x_title">
  <h2> <?=$model->isNewRecord ? "<i class='glyphicon glyphicon-plus'></i> NUEVO REGISTRO DE ATENCIÓN" : "<i class='glyphicon glyphicon-pencil'></i> ACTUALIZAR REGISTRO DE ATENCIÓN" ; ?>
    <? if(isset($model->usuario) && ($model->usuario->id !== Yii::$app->user->identity->id )){  ?>
      <span style="color:red">(Solo puede modificar el registro el usuario que lo creo) </span>
    <? } ?>
</h2>


    <div class="clearfix"> <div class="nav navbar-right panel_toolbox"><?=Html::button('<i class="glyphicon glyphicon-arrow-left"></i> Atrás',array('name' => 'btnBack','onclick'=>'js:history.go(-1);returnFalse;','id'=>'botonAtras')); ?></div>
</div>
  </div>

    <div class='row'>
      <div class="x_panel" >
        <legend class="text-info"><small>DATOS DEL REGISTRO</small></legend>
        <div class="x_content" style="display: block;">
          <?
            $form = ActiveForm::begin();
            $maporganismo = ArrayHelper::map(Organismo::find()->all() , 'id',  'nombre'  );
            $maptiporeg = ArrayHelper::map(Tiporeg::find()->all() , 'id',  'descripcion'  );
          ?>
          <div class='col-sm-3'>
            <label >Paciente: <span id='paciente'> </span>
              <button onclick="quitarSeleccion()"  title="Busqueda avanzada de paciente" type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bs-paciente-modal-lg" style="margin-left: 10px;"><i class="glyphicon glyphicon-search" ></i></button>
              <?   echo  Html::a('<i class="glyphicon glyphicon-plus"> Crear paciente</i>', ['paciente/create'],
               ["target"=>"_blank",'title'=> 'Crear nuevo paciente','class'=>'btn btn-primary btn-xs']); ?>
            </label>
              <input type="text" id="pacientebuscar" name="PacienteSearch[num_documento]"  placeholder="Ingresar DNI del paciente" >
              <button id="button_paciente" type="button" class ="btn btn-primary btn-xs" onclick='pacienteba();'>Buscar y añadir</button>
          </div>

          <div class='col-sm-3'>
            <label> Paciente </label></br>
            <input id="registroatencion-paciente" class="form-control"  style="width:250px;" value='<?=($model->paciente)?$model->paciente->apellido.", ".$model->paciente->nombre:''; ?>' type="text" readonly>
            <?=$form->field($model, 'id_paciente')->hiddenInput()->label(false);
            echo $form->field($model, 'id_tiporeg')->widget(
              Chosen::className(), [
               'items' => $maptiporeg,
               'clientOptions' => [
                 'rtl'=> true,
                   'search_contains' => true,
                   'single_backstroke_delete' => false,
               ],])->label("Tipo");
                ?>
           </div>
           <div class='col-sm-3'>
                <?=$form->field($model, 'fecha')->widget(DateControl::classname(), [
                            'options' => ['placeholder' => 'Ingrese fecha (opcional)',
                            'value'=> ($model->fecha)?$model->fecha:"" ,
                                    ],
                            'type'=>DateControl::FORMAT_DATE,
                            'autoWidget'=>true,
                            'displayFormat' => 'php:d/m/Y',
                            'saveFormat' => 'php:Y-m-d',
                          ])->label('Fecha');

                  echo $form->field($model, 'id_organismo')->widget(
                    Chosen::className(), [
                     'items' => $maporganismo,
                     'clientOptions' => [
                       'rtl'=> true,
                         'search_contains' => true,
                         'single_backstroke_delete' => false,
                     ],])->label("Organismo");

                     ?>
            </div>
            <div class='col-sm-3'>
                <?=$form->field($model, 'numero_nota')->textInput(['style'=> 'font-size:23px;color:red;','disabled'=>(isset($model->estado) && ($model->estado->descripcion=="LISTO" && !Usuario::isPatologo()))]) ; ?>
                <?=$form->field($model, "motivo")->textarea(["rows" => 4]) ; ?>
                <?=$form->field($model, 'id_usuario')->hiddenInput(["value"=>Yii::$app->user->identity->id])->label(false); ?>

            </div>
          </div>
          </div>
          <div class="x_panel" >

         <div class="x_content">
               <div class="modal fade bs-paciente-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                 <div class="modal-dialog modal-lg">
                   <div class="modal-content">
                     <div class="modal-body">
                       <div class="paciente-index">
                           <div id="ajaxCrudDatatable">
                             <?=GridView::widget([
                                 'id'=>'crud-paciente',
                                 'dataProvider' => $dataProviderPac,
                                 'filterModel' => $searchModelPac,
                                 'pjax'=>true,
                                 'columns' => require(__DIR__.'/_columnsPaciente.php'),
                                 'toolbar'=> [

                                 ],
                                 'panel' => [
                                     'type' => 'primary',
                                     'heading'=> false,
                                 ]
                             ])?>
                           </div>
                       </div>
                       <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                         <button type="button"  onclick='agregarFormularioPac();' class="btn btn-primary">Agregar al formulario</button>
                       </div>
                 </div>
               </div>
             </div>
         </div>
       </div>


      <?  if (!Yii::$app->request->isAjax){ ?>
         <div class='pull-right'>
            <?=Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','disabled'=>(isset($model->usuario) && ($model->usuario->id !== Yii::$app->user->identity->id ))]); ?>
         </div>
      <? }
          $form = ActiveForm::end();
      ?>

    </div>
   </div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

<script>

  var input = document.getElementById("pacientebuscar");
  input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
     event.preventDefault();
     document.getElementById("button_paciente").click();
    }
  });

  function pacienteba(){
    $.ajax({
          url: '<?php echo Yii::$app->request->baseUrl. '/index.php?r=paciente/search' ?>',
          type: 'get',
          data: {
                "PacienteSearch[num_documento]":$("#pacientebuscar").val() ,
                _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                },
          success: function (data) {
            var content = JSON.parse(data);
            if (content.status=='error'){
              swal(
              content.mensaje ,
              'PRESIONAR OK',
              'error'
              )
            }else{
              swal({
                   title: "Confirmado!",
                   text: "Se agrego el paciente",
                   type: "success",
                   timer: 800
                 });

            document.getElementById("registroatencion-paciente").value= content['apellido']+", "+content['nombre'];
            document.getElementById("registroatencion-id_paciente").value= content['id'];
           }
          }
     });

  }


///script agregar y quitar paciente desde la busqueda avanzada

  function agregarFormularioPac (){
    if ($("tr.success").find("td:eq(1)").text() != ""){
      document.getElementById("registroatencion-paciente").value= $("tr.success").find("td:eq(3)").text() +", "+ $("tr.success").find("td:eq(2)").text() ;
      document.getElementById("registroatencion-id_paciente").value=$("tr.success").find("td:eq(1)").text();
      //vacias el contenido de la variable para que no se anexe con otra eleccion de otro campo
      $('span.kv-clear-radio').click();
      $('button.btn.btn-default').click();

      swal({
           title: "Confirmado!",
           text: "Se agrego el paciente",
           type: "success",
           timer: 800
           })
         }
         else {
           swal(
           'No se ha seleccionado a ningún paciente' ,
           'PRESIONAR OK',
           'error'
         );
         }

  }
  function quitarSeleccion (){
    $('span.kv-clear-radio').click();

  }


</script>
