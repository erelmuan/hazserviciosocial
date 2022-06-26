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
use app\models\Area;
use app\models\Tiporeg;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;
use nex\chosen\Chosen;
use app\models\Usuario;
use kartik\depdrop\DepDrop;


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
            $maparea = ArrayHelper::map(Area::Find()->where(['id_organismo' => $model->id_organismo])->all() , 'id', 'nombre');
            // $maparea = ArrayHelper::map(Area::find()->all() , 'id',  'nombre'  );
            $maptiporeg = ArrayHelper::map(Tiporeg::find()->all() , 'id',  'descripcion'  );
          ?>

          <div class='col-sm-3'>
            <label> Paciente </label></br>
            <input id="registroatencion-paciente" class="form-control"  style="width:250px;" value='<?=($paciente)?$paciente->apellido.", ".$paciente->nombre:''; ?>' type="text" readonly>
            <?=$form->field($paciente, 'id')->hiddenInput(['name'=>"Registroatencion[id_paciente]"])->label(false);
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
             <?=$form->field($model, 'numero_nota')->textInput([ 'value'=> $numero_insertar,'style'=> 'font-size:23px;color:red;','readOnly'=>true]) ; ?>
             <?= $form->field($model, 'id_organismo')->dropDownList($maporganismo, ['id'=>'id_organismo',    'prompt'=>'- Seleccionar organismo'])->label('Organismo') ;?>

              <?
               // echo    $form->field($model, 'id_organismo')->widget(
               //      Chosen::className(), [
               //       'items' => $maporganismo,
               //       'clientOptions' => [
               //         'rtl'=> true,
               //           'search_contains' => true,
               //           'single_backstroke_delete' => false,
               //       ],])->label("Organismo");

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
                        ])->label('Fecha'); ?>

                <?
                 // echo $form->field($model, 'id_area')->textInput()->label("Area");
                 echo $form->field($model, 'id_area')->widget(DepDrop::classname(), [
                     'data'=>$maparea,
                     'options'=>['id'=>'id_area'],
                     'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                     'pluginOptions'=>[
                       'depends'=>['id_organismo'],
                        'placeholder'=>'Seleccionar area...',
                        'url'=>Url::to(['/registroatencion/subcat'])
                     ]
                 ])->label('Area');

                 ?>
                <?=$form->field($model, 'id_usuario')->hiddenInput(["value"=>Yii::$app->user->identity->id])->label(false); ?>

            </div>
            <div class='col-sm-3'>
                <?=$form->field($model, "motivo")->textarea(["rows" => 5]) ; ?>

                <?= $form->field($model, 'num_nota_automatico')->checkBox(['label' => 'Numero de nota automatico',
                    'onclick' => 'cambioNumnotaAutomatico();', 'checked' => '1','value' => '1']); ?>
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
   </div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

<script>
function cambioNumnotaAutomatico(){
    if(document.getElementById("registroatencion-num_nota_automatico").value==1 ){
      document.getElementById("registroatencion-numero_nota").readOnly = false;
      document.getElementById("registroatencion-num_nota_automatico").value =0;

    }else {
        $.ajax({
            url: '<?php echo Url::to(['/registroatencion/buscarnumnota']) ?>',
           type: 'get',
           data: {
                 _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                 },
           success: function (data) {
               var content = JSON.parse(data);
              document.getElementById("registroatencion-numero_nota").value=  content.numero_nota;
           }

      });
      document.getElementById("registroatencion-num_nota_automatico").value =1;
      document.getElementById("registroatencion-numero_nota").readOnly = true;
    }
}


</script>
