<?php
use yii\helpers\Url;

return [
      [
      'class'=>'\kartik\grid\DataColumn',
      'attribute'=>'id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'codigo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'material',
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'materialdiagnostico',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action,'id'=>$key]);
        },
  
    ],

];
