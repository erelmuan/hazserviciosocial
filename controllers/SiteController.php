<?php
namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//modelos
use app\models\Paciente;
use app\models\Provincia;
use app\models\Localidad;
use app\models\Usuario;
use app\models\Auditoria;
use app\models\Rol;
use app\models\Modulo;
use app\models\Accion;
use app\models\Barrio;
use app\models\Tipoprofesional;
use app\models\Obrasocial;
use app\models\Nacionalidad;
use app\models\Tipodoc;
use app\models\Registroatencion;
use app\models\Organismo;
use app\models\Area;
use app\models\Empresa;
use app\models\Tipodom;


use app\components\Seguridad\Seguridad;
class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return ['access' => ['class' => AccessControl::className() , 'only' => ['logout', 'administracion'], 'rules' => [['actions' => ['logout'], 'allow' => true, 'roles' => ['@'], ], [
        //El administrador tiene permisos sobre las siguientes acciones
        'actions' => ['administracion'], 'allow' => true,
        //Usuarios autenticados, el signo ? es para invitados
        'roles' => ['@'], 'matchCallback' => function ($rule, $action) {
            if (Yii::$app
                ->user
                ->identity->id_pantalla == 1) {
                return false;
            }
            else {
                return true;
            }
        }
        ], ], ], 'verbs' => ['class' => VerbFilter::className() , 'actions' => ['logout' => ['post'], ], ], ];
    }
    public function actionFlash() {
        $session = Yii::$app->session; // establece un mensaje flash llamado "greeting "
        $session->setFlash('saludo ', 'Hola usuarioator! ');
        return $this->render('flash');
    }
    /**
     * {@inheritdoc}
     */
    public function actions() {
        return ['error' => ['class' => 'yii\web\ErrorAction', ], 'captcha' => ['class' => 'yii\captcha\CaptchaAction', 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null, ], ];
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $cantidadRegistrosatencion = Registroatencion::find()->count();
        $cantidadPacientes = Paciente::find()->count();
        return $this->render('index', [ 'cantidadRegistrosatencion' => $cantidadRegistrosatencion,  'cantidadPacientes' => $cantidadPacientes ]);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        $this->layout = 'main2';
        if (!Yii::$app
            ->user
            ->isGuest) {
            /* Al entrar al sistema aparecera la pagina de login (return $this->render('login'),puesto
            que no entra a este if y tampoco al siguiente ( porque no esta logueado
            y por lo tanto  es invitado ni valida el post)*/
            /* Si se loguea entonces, pasa de largo la primera vez el isGuest
            entra al segundo if, se valida return goback hace volver al loguin
            de esa forma ahi si entra al primer if, y se dirige al pagina de inicio home() */
            /*Si vuelve para atras una vez logueado se redigira al primer if */
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app
            ->request
            ->post()) && $model->login()) {
            if (!Yii::$app
                ->user
                ->identity
                ->activo) {
                Yii::$app
                    ->user
                    ->logout();
            }
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', ['model' => $model, ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app
            ->user
            ->logout();
        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app
            ->request
            ->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app
                ->session
                ->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model, ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionExtras() {
        $cantidadArea = Area::find()->count();
        $cantidadProvincia = Provincia::find()->count();
        $cantidadLocalidad = Localidad::find()->count();
        $cantidadObrasocial = Obrasocial::find()->count();
        $cantidadNacionalidad = Nacionalidad::find()->count();
        $cantidadTipoDoc = Tipodoc::find()->count();
        $cantidadOrganismo = Organismo::find()->count();
        $cantidadBarrio = Barrio::find()->count();
        $cantidadEmpresa = Empresa::find()->count();
        $cantidadTipodom = Tipodom::find()->count();
        return $this->render('extras', ['cantidadArea' => $cantidadArea, 'cantidadProvincia' => $cantidadProvincia, 'cantidadLocalidad' => $cantidadLocalidad, 'cantidadBarrio' => $cantidadBarrio, 'cantidadObrasocial' => $cantidadObrasocial, 'cantidadNacionalidad' => $cantidadNacionalidad, 'cantidadTipoDoc' => $cantidadTipoDoc, 'cantidadTipodom'=>$cantidadTipodom,'cantidadOrganismo' => $cantidadOrganismo,'cantidadEmpresa' => $cantidadEmpresa ]);
    }

    public function actionAdministracion() {
        $cantidadUsuarios = Usuario::find()->count();
        $cantidadAuditorias = Auditoria::find()->count();
        return $this->render('administracion', ['cantidadUsuarios' => $cantidadUsuarios, 'cantidadAuditorias' => $cantidadAuditorias]);
    }
    public function actionPermisos() {
        $cantidadRoles = Rol::find()->count();
        $cantidadModulos = Modulo::find()->count();
        $cantidadAcciones = Accion::find()->count();
        return $this->render('permisos', ['cantidadRoles' => $cantidadRoles, 'cantidadModulos' => $cantidadModulos, 'cantidadAcciones' => $cantidadAcciones]);
    }

    public function actionConstruccion() {
        return $this->render('construccion');
    }
}
