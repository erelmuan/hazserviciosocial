<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [

      [
          'class'=>'\kartik\grid\DataColumn',
          'attribute'=>'id',
      ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'numero_nota',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'paciente',
        'width' => '170px',
        'value' => function($model) {
          return Html::a( $model->paciente->nombre .' '.$model->paciente->apellido,['paciente/view',"id"=> $model->paciente->id]
            ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
           );
         },
         'filterInputOptions' => ['class' => 'form-control',  'placeholder' => 'DNI o apellido'],
         'format' => 'raw',

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'motivo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tiporeg',
        'label'=> 'Tipo de reg.',
        'value'=>'tiporeg.descripcion'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo',
        'label'=>'organismo/institución',
        'width' => '170px',
        'value' => function($model) {
          return Html::a( ($model->organismo)?$model->organismo->nombre:'No definido' ,['organismo/view',"id"=> ($model->organismo)?$model->organismo->id:'No definido']
            ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del organismo','data-toggle'=>'tooltip']
           );
         },
         'filterInputOptions' => ['class' => 'form-control',  'placeholder' => 'Nombre del organismo'],
         'format' => 'raw',

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
    ],


    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'localidad',
        'label'=> 'Localidad',
        'value'=>'historicodomicilio.localidad.nombre'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'barrio',
        'label'=> 'Barrio',
        'value'=>'historicodomicilio.barrio.nombre'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'usuario',
        'label'=> 'Usuario',
        'value'=>'usuario.nombre'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'area',
        'label'=> 'Area/Sector',
        'width' => '170px',
        'value' => 'area.nombre'

    ],

    [
      'class'=>'\kartik\grid\BooleanColumn',
      'label'=> 'Nota',
      'attribute'=>'nota',
      'value'=>'nota'


   ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],
    ],

];
