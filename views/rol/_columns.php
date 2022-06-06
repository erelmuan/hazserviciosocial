<?php
use yii\helpers\Url;
use kartik\grid\GridView;
return [
    [
      'class' => '\kartik\grid\ExpandRowColumn',
      'value' => function ($model) {
        //id=1 administrador
          if ($model->id ==1){
            return false;
            }
            else {
              return GridView::ROW_COLLAPSED;
            }

      },
      'detailUrl' => Url::to(['listdetalle']),   //  action mostrarDetalle con POST expandRowKey como ID
      'detailRowCssClass' => 'expanded-row',
      'expandIcon' => '<i class="glyphicon glyphicon-plus" style="color:black"></i>',
      'collapseIcon' => '<i class="glyphicon glyphicon-minus" style="color:black"></i>',
      'expandOneOnly' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action,'id'=>$key]);
        },

    ],


];
