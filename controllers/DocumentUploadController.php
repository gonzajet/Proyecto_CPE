<?php

namespace app\controllers;

use Yii;
use app\models\DocumentUpload;
use app\models\DocumentUploadSearch;
use app\models\HistoryDocumentUploadSearch;
use app\models\Estado;
use app\models\Programa;
use app\models\Moderw;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\commands\RoleAccessChecker;
use app\commands\RegisterModeChecker;
use app\commands\Log;
use app\commands\Stadistics;
use app\models\Historial;
/**
 * DocumentUploadController implements the CRUD actions for DocumentUpload model.
 */
class DocumentUploadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [
              'access' => [
                 'class' => AccessControl::className(),
                 'only' => ['index', 'view', 'update', 'create', 'delete',],
                 'rules' => [
                     [
                         'allow' => true,
                         'actions' => ['',],
                         'roles' => ['?'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['index', 'view', 'update', 'create', 'delete',],
                         'roles' => ['@'],
                     ],
                     [
                         'allow' => false,
                         'actions' => ['',],
                         'roles' => ['@'],
                     ],
                 ],
             ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DocumentUpload models.
     * @return mixed
     */
    public function actionIndex(){
	
			try{
				$searchModel = new DocumentUploadSearch();
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
				//$msg='El resultado de test estadistico es: '
				//.Stadistics::test()
				//;
                                $msg = "";
				return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'msg'=>$msg,
				]);
			} catch (\yii\db\Exception $e) {
                             Log::file_force_contents("log.txt", $e->getMessage());
                            return $this->redirect(['error/db-grant-error',]);
                            
                        }

    }

    public function actionHistorial($id){
        $searchModel = new DocumentUploadSearch();
        $dataProvider = $searchModel->searchPorIdArchivoPrograma($id);
		return $this->render('historial_estados', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionHistorial2($planmateriaId){
        $searchModel = new Historial();
        $dataProvider = $searchModel->searchPorProgramaId($planmateriaId);
		return $this->render('historial', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single DocumentUpload model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
		
			try{
				return $this->render('view', [
					'model' => $this->findModel($id),
				]);
			} catch (\yii\db\Exception $e) {
                             Log::file_force_contents("log.txt", $e->getMessage());
                            return $this->redirect(['error/db-grant-error',]);
                            
                        }
	
    }

    /**
     * Creates a new DocumentUpload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
		
			try{
				$model = new DocumentUpload();
				$subModelEstado = new Estado();
				$subModelPrograma = new Programa();
				$subModelModerw = new Moderw();
				if ($model->load(Yii::$app->request->post())){
					$a=RegisterModeChecker::isInstanceDocument($model->programa_id);
					if($a != false){$a->moderw_id=64;$a->save();}/* Se etiqueta como inaccesible el documento */
					$model->usuario_id=Yii::$app->user->identity->usuario_id;
					$model->archivo= UploadedFile::getInstance(	$model,'archivo');
					$model->fecha=date('Y-m-d');
					if (($model->save())&&($model->upload())){
						$new=RegisterModeChecker::formatDocument($model);
						$model->archivo=$new;$model->save();
						return $this->redirect(['index', 'id' => $model->archivoprograma_id]);
					}
				} else return $this->render('create', ['model' => $model,
						'subModelEstado' => $subModelEstado,
						'subModelPrograma' => $subModelPrograma,
						'subModelModerw' => $subModelModerw,]);
			} catch (\yii\db\Exception $e) 
                        {
                             Log::file_force_contents("log.txt", $e->getMessage());
                            return $this->redirect(['error/db-grant-error',]);
                            
                        }
	
	}
	
    /**
     * Updates an existing DocumentUpload model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){

			try{
				$model = $this->findModel($id);
				$subModelEstado = new Estado();
				$subModelPrograma = new Programa();
				$subModelModerw = new Moderw();
                                $historialModel = new Historial();
                                $usuario = Yii::$app->user->identity->usuario_id;
                                
				if ($model->load(Yii::$app->request->post()) && $historialModel->load(Yii::$app->request->post()) ){
                                    $model->usuario_id=$usuario;
                                    $model->fecha=date('Y-m-d');

                                    $historialModel->usuario_id = $usuario;
                                    $historialModel->programa_id = $model->programa_id;
                                    $historialModel->archivoprograma_id = $model->archivoprograma_id;
                                    $historialModel->archivo= UploadedFile::getInstance($historialModel,'archivo');
                                    if ($historialModel->archivo != '')
                                    {
                                        $historialModel->upload();
                                        $new=RegisterModeChecker::formatDocument($historialModel);
                                        $historialModel->archivo=$new;
                                        $model->archivo=$new;
                                    }
                                    $historialModel->save();
                                    
                                    if ($model->save()) return $this->redirect(['index', 'id' => $model->archivoprograma_id]);
				} 
                                else return $this->render('update', [
							'model' => $model,
							'subModelEstado' => $subModelEstado,
							'subModelPrograma' => $subModelPrograma,
							'subModelModerw' => $subModelModerw,
                                                        'historialModel' => $historialModel,
                                                        ]);
			} catch (\yii\db\Exception $e) { 
                            
                            Log::file_force_contents("log.txt", $e->getMessage());
                            return $this->redirect(['error/db-grant-error',]);
                        }
	
    }
    
    
    /**
     * Deletes an existing DocumentUpload model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
	
			try{
				$this->findModel($id)->delete();
				return $this->redirect(['index']);
			} catch (\yii\db\Exception $e) 
                        {
                             Log::file_force_contents("log.txt", $e->getMessage());
                            return $this->redirect(['error/db-grant-error',]);
                            
                        }
	
    }
    
    /**
     * Lists all Archivoprograma models.
     * @return mixed
     */
    public function actionPrograma($idprograma){
		//if (RoleAccessChecker::actionIsAsignSector('archivoprograma/programa')) {
			$searchModel = new DocumentUploadSearch();
			$dataProvider = $searchModel->searchPorIdPrograma($idprograma);
			//$msg='El resultado de test estadistico es: '
			//	.Stadistics::test()
			//	;
                        $msg = "";
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'msg'=>$msg,
			]);
		//} else return $this->redirect(['error/level-access-error',]);
    }

    /**
     * Finds the DocumentUpload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentUpload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if (($model = DocumentUpload::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
