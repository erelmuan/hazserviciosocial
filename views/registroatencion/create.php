<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Registroatencion */

?>
<div class="registroatencion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'paciente'=>$paciente,
    ]) ?>
</div>
