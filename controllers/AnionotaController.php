<?php
namespace app\controllers;

use Yii;
use app\models\Anionota;
use app\models\AnionotaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * AnionotaController implements the CRUD actions for Anionota model.
 */
class AnionotaController extends Controller
{

    public function validar($valor_enviado, $model) {
        if ($valor_enviado["activo"] == false && $model->activo == true) {
            $this->setearMensajeError('NO SE PUEDE DESELECCIONAR DIRECTAMENTE EL ACTIVO, DEBE SELECCIONAR ACTIVO OTRA AÑO ');
            return false;
        }
        if ($valor_enviado["activo"] == true && $model->activo == false) {
                 $model->actualizarEstado();
             }
        return true;
        //si tiene solicitudes no se podra modificar el año

    }
    /**
     * Lists all Anionota models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AnionotaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Anionota model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Año - nota #".$id,
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
     * Creates a new Anionota model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Anionota();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo Año nota",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo Año nota",
                    'content'=>'<span class="text-success">Crear Año nota satisfactoriamente</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
                return [
                    'title'=> "Crear nuevo Año nota",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    /**
     * Updates an existing Anionota model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      $request = Yii::$app->request;
      $model = $this->findModel($id);
      if ($this->request->isPost) {
          //Si no se valida entonces mostrar $mensaje
          if (!$this->validar($_POST["Anionota"], $model)) {
              return $this->render('update', ['model' => $model, ]);
          }
          if ($_POST["Anionota"]["activo"] == true && $model->activo == false) {
              $model->actualizarEstado();
          }
          if ($model->load($request->post()) && $model->save()) {
              return $this->redirect(['view', 'id' => $model->id]);
          }
      }
      else {
          return $this->render('update', ['model' => $model, ]);
      }
    }



    /**
     * Delete an existing AnioProtocolo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if (Registroatencion::getRegistrosAnio($model->anio)) {
            $this->setearMensajeError('NO SE PUEDE ELIMINAR PORQUE HAY REGISTROS EN ESE AÑO');
            return $this->redirect(['index']);
        }
        else {
            $this->findModel($id)->delete();
            Yii::$app
                ->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        }
    }
    /**
     * Finds the Anionota model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Anionota the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Anionota::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
