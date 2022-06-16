<?php

namespace app\controllers;

use Yii;
use app\models\Registroatencion;
use app\models\RegistroatencionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Paciente;
use app\models\PacienteSearch;
use app\components\Metodos\Metodos;
use app\components\behaviors\AuditoriaBehaviors;
use yii\filters\AccessControl;
use app\components\Seguridad\Seguridad;

/**
 * RegistroatencionController implements the CRUD actions for Registroatencion model.
 */
class RegistroatencionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Registroatencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Registroatencion();
        $searchModel = new RegistroatencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $columnas = Metodos::obtenerColumnas($model);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns'=>$columnas
        ]);
    }


    /**
     * Displays a single Registroatencion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Registro de atención #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Registroatencion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate($id_paciente=NULL) {
         $request = Yii::$app->request;
         $model = new Registroatencion();
         ////////////PACIENTE/////////////////
         $modelpaciente = new Paciente();
         $paciente= Paciente::findOne($id_paciente);
         //HISTORICO DE DOMICILIOS PRINCIPALES  CREAR EL MODELO PARA GUARDAR!!!!
         if ($this->request->isPost) {
             if ($model->load($request->post()) && $model->save()) {
                 return $this->redirect(['view', 'id' => $model->id]);
             }
         }
            return $this->render('_form', ['model' => $model, 'paciente' => $paciente ]);

     }


    /**
     * Updates an existing Registroatencion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        ////////////PACIENTE/////////////////
        $modelPac = new Paciente();
        $searchModelPac = new PacienteSearch();
        $dataProviderPac = $searchModelPac->search(Yii::$app->request->queryParams);
        $dataProviderPac->pagination->pageSize = 7;
        //HISTORICO DE DOMICILIOS PRINCIPALES  CREAR EL MODELO PARA GUARDAR!!!!

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_paciente]);
        } else {
          return $this->render('_form', ['model' => $model, 'searchModelPac' => $searchModelPac, 'dataProviderPac' => $dataProviderPac, 'modelPac' => $modelPac,   ]);
        }

    }
    /**
     * Delete an existing Solicitud model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionSelect() {
        $request = Yii::$app->request;
        $model = new Registroatencion();
        if ($request->isAjax) {
            Yii::$app
                ->response->format = Response::FORMAT_JSON;
            if (isset($_POST['seleccion'])) {
                // recibo datos de lo seleccionado, reconstruyo columnas
                $seleccion = $_POST['seleccion'];
                $columnAdmin = $model->attributeColumns();
                $columnSearch = [];
                $columnas = [];
                foreach ($columnAdmin as $value) {
                    $columnSearch[] = $value['attribute'];
                }
                foreach ($seleccion as $key) {
                    $indice = array_search($key, $columnSearch);
                    if ($indice !== null) {
                        $columnas[] = $columnAdmin[$indice];
                    }
                }
                // guardo esa informacion, sin controles ni excepciones, no es importante
                $vista = \app\models\Vista::findOne(['id_usuario' => Yii::$app
                    ->user->id, 'modelo' => $model->classname() ]);
                if ($vista == null) {
                    $vista = new \app\models\Vista();
                    $vista->id_usuario = Yii::$app
                        ->user->id;
                    $vista->modelo = $model->classname();
                }
                $vista->columna = serialize($columnas);
                $vista->save();
                return [$vista, 'forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
            }
            // columnas mostradas actualmente
            $columnas = Metodos::obtenerColumnas($model);
            // attributos de las columnas mostradas
            $seleccion = Metodos::obtenerAttributosColumnas($columnas);
            // todas las etiquetas
            $etiquetas = Metodos::obtenerEtiquetasColumnas($model, $seleccion);
            return ['title' => "Personalizar Lista", 'content' => $this->renderAjax('/../components/Vistas/_select', ['seleccion' => $seleccion, 'etiquetas' => $etiquetas, ]) , 'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) . Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]) ];
        }
        else {
            // Process for non-ajax request
            return $this->redirect(['index']);
        }
    }
    /**
     * Delete an existing Registroatencion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model= $this->findModel($id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->usuario->id !== Yii::$app->user->identity->id) {
            return ['title' => "Eliminar Registro de atención #" . $id, 'content' => "Solo puede eliminar el registro el usuario que lo creo", 'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) ];
        }
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }


    /**
     * Finds the Registroatencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Registroatencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registroatencion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
