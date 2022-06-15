<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Alert;
use kartik\widgets\Growl;
// use kartik\icons\Icon;
use kartik\widgets\SwitchInput;

use app\models\AnioProtocolo;


$bundle = yiister\gentelella\assets\Asset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--Plantilla para modificar el link logout-->
     <?= Html::cssFile('@web/css/botonlogout.css') ?>
     <?= Html::cssFile('@web/css/plantillas-intro.css') ?>

     <!-- efecto sobre los modulos  -->
     <?= Html::cssFile('@web/css/animate.min.css') ?>
     <?= Html::jsFile('@web/js/jquery.min.js') ?>
     <!-- Modal para que muestra el protocolo -->

     <?= Html::jsFile('@web/js/sweetalert2.all.min.js') ?>
     <?= Html::jsFile('@web/js/flashjs/dist/flash.min.js') ?>
     <style>
      #demo{
        position:absolute;
        right:123px;
      top: 40px;     }
      .panel-primary > .panel-heading {
          color: #fff;
          background-color: #333;
          border-color: #977979;
      }
      .btn-circle {
          width: 25px;
          height: 25px;
          /* padding: 6px 0px; */
          border-radius: 15px;
          text-align: center;
          font-size: 12px;
          line-height: 1.42857;
      }

     </style>
</head>

<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >


<?php $this->beginBody(); ?>

<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">

            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <center class="site_title"><i class="fa fa-flask"></i> <span>hazserviciosocial</span> </center><center id="version" style="color:white; font-size: 10px;">Version: 1.0.0</center>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                    <? echo '<img src=uploads/avatar/sm_'.Yii::$app->user->identity->imagen.' class="img-circle profile_img"   alt="..." >';?>
                    </div>
                    <div class="profile_info">
                        <span>BIENVENIDO,</span>
                        <h2><?=Yii::$app->user->identity->nombre ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

              </br>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <p>
                        <h3>MENÚ</h3>
                        </p>
                      <?  if (Yii::$app->user->identity->id_pantalla==1){ ?>
                        <?= //Hacerlo dinamico recorriendo un arreglo
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                                "items" => [
                                    ["label" => "Inicio", "url" => "index.php", "icon" => "fa fa-home"],
                                    ["label" => "Biopsias", "url" => ["/biopsia/index","sort"=>"-id"] , 'icon' =>"fa icon-microscope"],
                                    ["label" => "Paps", "url" => ["/pap/index","sort"=>"-id"], "icon" => "fa fa-flask"],

                                ],
                            ]
                        );
                      }else { ?>
                        <?= //ELIMINE LOS PREFIJOS EN LOS ARCHIVOS FontAwesome.php e Icon para que se puedan ver los iconos
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                                "items" => [
                                    ["label" => "Inicio", "url" => ["/site/index"], "icon" => "fa fa-home"],
                                    ["label" => "Pacientes", "url" => ["/paciente/index"], "icon" => "fa fa-group"],
                                    ["label" => "Registros de Aten.", "url" => ["/registroatencion/index","sort"=>"-id"], "icon" => "fa fa-file-text-o"],
                                    ["label" => "Auditoria","url" => ["/auditoria/index","sort"=>"-id"], "icon" => "fa fa-book"],


                                ],
                            ]
                        );
                      }
                        ?>
                        <? if(empty($_SESSION['mostrar']) || $_SESSION['mostrar']=="bienvenido" ){  ?>
                        <div id="loader-out">
                          <div id="loader-container">
                            <p id="loading-text">BIENVENIDO <?=Yii::$app->user->identity->username ?> </p>
                          </div>
                        </div>
                        <?
                        $_SESSION['mostrar']="nomostrar";
                        }
                        ?>


                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a href=<?=Yii::$app->homeUrl."?r=site/administracion"; ?> data-toggle="tooltip" data-placement="top" title="Administración">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Ayuda">
                        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                    </a>
                    <a href=<?=Yii::$app->homeUrl."?r=usuario/perfil"; ?> data-toggle="tooltip" data-placement="top" title="Perfil">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                    </a>
                    <?
                    echo Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        '<span  class="glyphicon glyphicon-off"  aria-hidden="true"></span>' .'<i data-toggle="tooltip" data-placement="top" title="" data-original-title="Ayuda" aria-hidden="true"   style="padding-top: 3px;"></i>',
                        ['class' => 'boton_3',
                        'title'=>"Salir"

                      ]
                    )
                    . Html::endForm()
                    . '</li>';
                    ?>
                    <!-- <a data-toggle="tooltip" data-placement="top" title="Salir">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a> -->
                </div>

                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">

                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                              <? echo '<img src=uploads/avatar/sm_'.Yii::$app->user->identity->imagen.'   alt="..." >';?>
                              <?=Yii::$app->user->identity->username ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href=<?=Yii::$app->homeUrl."?r=usuario/perfil"; ?>> Perfil</a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Configuración</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">Ayuda</a>
                                </li>
                              </li>
                                <?=
                                '<li>'
                                . Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                  'Salir'.  '<i class="fa fa-sign-out pull-right" style="padding-top: 3px;"></i>',
                                    ['class' => 'boton_2']
                                )
                                . Html::endForm()
                                . '</li>'

                                ?>


                              </li>
                            </ul>

                        </li>


                        <div id="fecha">
                         <h2>
                            <p id="demo"></p>
                          </h2>
                        </div>

                    </ul>


                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
                        <?php
                        echo \kartik\widgets\Growl::widget([
                            'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
                            'showSeparator' => true,
                            'delay' => 0, //This delay is how long before the message shows
                            'pluginOptions' => [
                              'showProgressbar' => true,
                                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                                'placement' => [
                                    'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                                    'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                                ]
                            ]
                        ]);
                        ?>
                    <?php endforeach; ?>

            <div class="clearfix"></div>

            <?= $content ?>


        </div>
        <!-- /page content -->
        <!-- footer content -->

        <footer>
            <div id="datosHospital">
                Hospital "Artémides ZATTI" - Rivadavia 391 - (8500) Viedma - Río Negro <br />
                Tel. 02920 - 427843 | Fax 02920 - 429916 / 423780
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>

<!-- /footer content -->
<?php $this->endBody(); ?>

<script>

$( document ).ready(function() {
  // Handler for .ready() called.
  setTimeout(function(){
    $('#loader-out').fadeOut();
  }, 1300);
});

</script>
<script>
var myVar = setInterval(myTimer, 1000);

function myTimer() {
    var d = new Date();
    document.getElementById("demo").innerHTML = d.toLocaleTimeString();
}
</script>
</body>
</html>
<?php $this->endPage(); ?>
