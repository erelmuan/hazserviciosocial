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
  <h2>  <i class='glyphicon glyphicon-plus'></i> CARGAR REGISTRO </h2>


    <div class="clearfix"> <div class="nav navbar-right panel_toolbox"><?=Html::button('<i class="glyphicon glyphicon-arrow-left"></i> Atrás',array('name' => 'btnBack','onclick'=>'js:history.go(-1);returnFalse;','id'=>'botonAtras')); ?></div>
</div>
  </div>

    <div class='row'>
      <div class="x_panel" >
        <legend class="text-info"><small>BUSQUEDA DEL PACIENTE</small></legend>
        <div class="x_content" style="display: block;">
          <?
            $form = ActiveForm::begin();
            $maporganismo = ArrayHelper::map(Organismo::find()->all() , 'id',  'nombre'  );
            $maptiporeg = ArrayHelper::map(Tiporeg::find()->all() , 'id',  'descripcion'  );
          ?>
          <div class='col-sm-3'>
            <label >Paciente: <span id='paciente'> </span>
              <button onclick="quitarSeleccion()"  title="Busqueda avanzada de paciente" type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bs-paciente-modal-lg" style="margin-left: 10px;"><i class="glyphicon glyphicon-search" > Busqueda avanzada</i></button>
            </label>
              <input type="text" id="pacientebuscar" name="PacienteSearch[num_documento]"  placeholder="Ingresar DNI del paciente" >
              <button id="button_paciente" type="button" class ="btn btn-primary btn-xs" onclick='pacienteba();'>Buscar y añadir</button>
          </div>

          <div class='col-sm-3'>
            <label> Paciente </label></br>
            <input type="hidden" id="registroatencion-id_paciente" class="form-control" name="Registroatencion[id_paciente]">
            <input id="registroatencion-paciente" class="form-control"  style="width:250px;"  type="text" readonly>

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
                                 'dataProvider' => $dataProvider,
                                 'filterModel' => $searchModel,
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
            <?//=Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','disabled'=>(isset($model->usuario) && ($model->usuario->id !== Yii::$app->user->identity->id ))]); ?>
         </div>
      <? }
          $form = ActiveForm::end();
      ?>

    </div>
   </div>

   <?= $this->render('_form', [
       'model' => $model,
   ]) ?>

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
