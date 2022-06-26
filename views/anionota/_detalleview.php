<?
use yii\widgets\DetailView;
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'anio',
        'activo:boolean',
    ],
]) ?>
