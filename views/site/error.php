<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
El error anterior ocurrió mientras el servidor web estaba procesando su solicitud.
    </p>
    <p>
Póngase en contacto con nosotros si cree que esto es un error del servidor. Gracias.
    </p>

</div>
