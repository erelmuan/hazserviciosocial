<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [


    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id',
        'hidden' => true
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apellido',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'num_documento',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'sexo',
    ],
    [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'fecha_nacimiento',
      'label'=> 'Fecha de nacimiento',
      'value'=>'fecha_nacimiento',
      'format' => ['date', 'd/M/Y'],
      'filterInputOptions' => [
          'id' => 'fecha1',
          'class' => 'form-control',
          'autoclose'=>true,
          'format' => 'dd/mm/yyyy',
          'startView' => 'year',
          'placeholder' => 'd/m/aaaa'

      ]
    ],
    [
      'class' => 'kartik\grid\ActionColumn',

      'dropdown' => false,
      'vAlign'=>'middle',
      'urlCreator' => function($action, $model, $key, $index) {
              return Url::to([$action,'id'=>$key]);
      },
      'template'=> '{fos}',
      'buttons'=> [
        'fos' => function ($url, $model, $key) {
             return
             //  Html::a(
             // '<span class="glyphicon glyphicon-list-alt">Planilla</span>', ['thturno/fos', 'id' => $key], ['class' => 'profile-link','target'=>'_blank','title'=>"Planilla FOS"]) ;
             // },
             Html::a('<i class="glyphicon glyphicon-copy"></i> Seleccionar paciente', ['/paciente/update', 'id' => $model->id], [
                  'class'=>'btn btn-primary',
                  // 'role'=>'modal-remote',
                  // 'target'=>'_blank',
                  'data-toggle'=>'tooltip',
                  'title'=>'Se añadira el paciente al registro de atención'
              ]);
             },
           ],
      'updateOptions'=>['title'=>'Actualizar', 'data-toggle'=>'tooltip','icon'=>"<button class='btn-primary btn-circle'><span class='glyphicon glyphicon-pencil'></span></button>"],

   ],



];
