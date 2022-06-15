<?php
namespace app\controllers;
use Yii;
use app\models\Paciente;
use app\models\PacienteSearch;
use app\models\Provincia;
use app\models\Domicilio;
use app\models\Localidad;
use app\models\Solicitud;
use app\models\Obrasocial;
use app\models\CarnetOsoc;
use app\controllers\CarnetOsocController;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\components\Metodos\Metodos;
use app\models\PacienteForm;
/**
 * PacienteController implements the CRUD actions for Paciente model.
 */
class PacienteController extends Controller {

    public function actionSearch() {
        $searchModelPac = new PacienteSearch();
        $searchModelPac->scenario = "search";
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $searchModelPac->load(\Yii::$app->request->get());
            if ($searchModelPac->validate()) {
                $dataProviderPac = $searchModelPac->search(\Yii::$app
                    ->request
                    ->get());
                if ($dataProviderPac->totalCount == 0) return Json::encode(['status' => 'error', 'mensaje' => "No se encontro el paciente"]);
                else return Json::encode(["nombre" => $dataProviderPac->getModels() [0]['nombre'], "apellido" => $dataProviderPac->getModels() [0]['apellido'], "id" => $dataProviderPac->getModels() [0]['id']]);
            }
            else {
                $errors = $searchModelPac->getErrors();
                return Json::encode(['status' => 'error', 'mensaje' => $errors['num_documento'][0]]);
            }
        }
    }
    /**
     * Lists all Paciente models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PacienteSearch();
        $dataProvider = $searchModel->search(Yii::$app
            ->request
            ->queryParams);
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, ]);
    }
    /**
     * Displays a single Paciente model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $request = Yii::$app->request;
        $carnet = CarnetOsocController::findidpacModel($id);
        $model = $this->findModel($id);
        $model->fecha_nacimiento = date('d/m/Y', strtotime($model->fecha_nacimiento));
        if ($request->isAjax) {
            Yii::$app
                ->response->format = Response::FORMAT_JSON;
            return ['title' => "Paciente #" . $id, 'content' => $this->renderAjax('view', ['model' => $model, 'carnet' => $carnet, ]) , 'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) ];
        }
        else {
            return $this->render('view', ['model' => $model, 'carnet' => $carnet, ]);
        }
    }

    public function actionCreate() {
        $model = new PacienteForm();
        $model->paciente = new Paciente;
        $model->paciente->loadDefaultValues();
        $model->setAttributes(Yii::$app->request->post());

        $valorObrasocial = [];
        $afiliado = [];
        $provincias = [];
        $localidades = [];
        $obrasociales = [];
        if (Yii::$app->request->post() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->paciente->id]);
        }
        else {
            return $this->render('create', ['model' => $model, 'provincias' => $provincias, 'localidades' => $localidades, 'obrasociales' => $obrasociales, 'valorObrasocial' => $valorObrasocial, 'afiliado' => $afiliado, ]);
          }
    }


    /**
     * Updates an existing Paciente model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $model = new PacienteForm();
        $model->paciente = $this->findModel($id);
        $model->setAttributes(Yii::$app->request->post());
        // $model->fecha_nacimiento = date('d/m/Y',strtotime($model->fecha_nacimiento));
        $valorObrasocial = [];
        $afiliado = [];
        $provincias = [];
        $localidades = [];
        $obrasociales = [];

        if (Yii::$app->request->post() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->paciente->id]);
        }
        else {
            return $this->render('update', ['model' => $model, 'provincias' => $provincias, 'localidades' => $localidades, 'obrasociales' => $obrasociales, 'valorObrasocial' => $valorObrasocial, 'afiliado' => $afiliado, ]);
        }

    }
    /**
     * Delete an existing Paciente model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
          if (Solicitud::find()->where(['id_paciente'=>$id])->count()>0 ){
            return ['title' => "Eliminar paciente #" . $id, 'content' => 'No se puede eliminar el paciente porque esta asociado a una solicitud', 'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) ];
          }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
            */
            Yii::$app
                ->response->format = Response::FORMAT_JSON;

                \Yii::$app
                    ->db
                    ->createCommand()
                    ->delete('carnet_os', ['id_paciente' => $id])->execute();
                if ($this->findModel($id)->delete()) {
                    return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
                }
        }
        else {
            /*
             *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionPuco() {
        //fin
        return $this->render('puco');
    }
    /**
     * Finds the Paciente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paciente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Paciente::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
