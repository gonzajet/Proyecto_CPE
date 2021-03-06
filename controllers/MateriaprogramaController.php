<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\controllers;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\commands\RoleAccessChecker;
use app\controllers\ErrorController;
use app\models\MateriaPrograma;
use app\models\Instituto;
use app\models\Carrera;
use app\models\Planestudio;
use app\models\Ano;
use app\models\MateriaProgramaSearch;
use yii\web\Response;
use yii\helpers\VarDumper;
use app\models\Usuariocarrera;


class MateriaprogramaController extends Controller
{
    public function actionIndex(){
        try
        {
            $model = new MateriaPrograma();
            $searchModel = new MateriaProgramaSearch();
            $dataProvider = $searchModel->search();
            $subModel = new Instituto();
            $carreras = Carrera::getAllCarreras();
            $planestudios = Planestudio::getAllPlanestudio();
            $cicloslectivos = Ano::getAllAnos();
            
            $esInstituto = RoleAccessChecker::actionIsAsignSector('rolinstituto');
            if ($esInstituto)
            {
                $usuario = Yii::$app->user->identity->usuario_id;
                $carreraId = Usuariocarrera::findOne(['usuario_id' => $usuario])->carrera_id; 
                $institutoId = Carrera::findOne(['carrera_id' => $carreraId])->instituto_id;
                $planestudios = Planestudio::findAll(['carrera_id' => $carreraId]);
                $model->instituto = $institutoId;
                $model->carrera = $carreraId;
            }
            
            return $this->render('index', ['model' => $model, 'esInstituto' => $esInstituto,'subModel' => $subModel,'carreras' => $carreras,'planestudios' => $planestudios,'cicloslectivos' => $cicloslectivos,'dataProvider' => $dataProvider ]);
 	}
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
    
    public function actionBuscarMaterias()
    {
        try
        {
            $model = new MateriaPrograma();
            $searchModel = new MateriaProgramaSearch();
            $dataProvider = $searchModel->search();
            $subModel = new Instituto();
            $subModel2 = new Carrera();
            
            
            return $this->render('index', ['model' => $model, 'subModel' => $subModel,
                                           'subModel2' => $subModel2,'dataProvider' => $dataProvider ]);
 	}
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
        
    }
    
    public function actionGetCarreras()
    {
        try
        {
            $request = Yii::$app->request;
            $post = $request->post();
            $id_instituto = $post['institutoId'];
            if ($request->isAjax && !empty($id_instituto)) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    /* Me traigo todas las carreras por instituto */
                    $carrera = Carrera::find()
                                ->joinWith('instituto')
                                ->where(['instituto.instituto_id' => $id_instituto])
                                ->all();
              
                    return $carrera;
            }

        }
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
    
    public function actionGetPlanEstudio()
    {
        try
        {
            $request = Yii::$app->request;
            $post = $request->post();
            $carrera_id = $post['carreraId'];
            if ($request->isAjax && !empty($carrera_id)) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    /* Me traigo los planes de estudio */
                    $planEstudio = Planestudio::find()
                                ->joinWith('carrera')
                                ->where(['carrera.carrera_id' => $carrera_id])
                                ->all();
                    
                    return $planEstudio;
            }

        }
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
    
    public function actionGetCicloLectivo()
    {
        try
        {
            $request = Yii::$app->request;
            $post = $request->post();
            if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    /* Me traigo los planes de estudio */
                    $anios = Ano::getAllAnos();
                    return $anios;
            }

        }
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
    
    
    public function actionGetProgramasMaterias($instituto,$carrera,$planestudio,$ciclolectivo)
    {
        try
        {
            $model = new MateriaPrograma();
            $model->instituto = $instituto;
            $model->carrera = $carrera;
            $model->planestudio = $planestudio;
            $model->ciclolectivo = $ciclolectivo; 
           
            $searchModel = new MateriaProgramaSearch();
            $esInstituto = RoleAccessChecker::actionIsAsignSector('rolinstituto');
            
            $dataProvider = $searchModel->buscarProgramas(intval($instituto),intval($carrera),intval($planestudio),intval($ciclolectivo));
            $subModel = new Instituto();
            $carreras = Carrera::getAllCarreras();
            $planestudios = Planestudio::getAllPlanestudio();
            $cicloslectivos = Ano::getAllAnos();
            return $this->render('index', ['model' => $model,'esInstituto' => $esInstituto, 'subModel' => $subModel,'carreras' => $carreras,'planestudios' => $planestudios,'cicloslectivos' => $cicloslectivos, 'dataProvider' => $dataProvider ]);
        }
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
}