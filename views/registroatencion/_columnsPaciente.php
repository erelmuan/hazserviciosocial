<?php
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\RadioColumn',
        'width' => '20px',
    ],
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
      //nombre
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
    


];
