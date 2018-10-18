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


class MateriaprogramaController extends Controller
{
    public function actionIndex(){
        try
        {
            $model = new MateriaPrograma();
            $searchModel = new MateriaProgramaSearch();
            $dataProvider = $searchModel->search();
            $subModel = new Instituto();
            
            return $this->render('index', ['model' => $model, 'subModel' => $subModel,'dataProvider' => $dataProvider ]);
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
                    //$instituto = Instituto::find()->where('instituto_id ='.$id_instituto)->one(); // me traigo el instituto
                    //yii\helpers\VarDumper::dump($instituto, 10, true);
                    //$myJSON = json_encode($carreras);
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
            $searchModel = new MateriaProgramaSearch();
          
            $dataProvider = $searchModel->buscarProgramas(intval($instituto),intval($carrera),intval($planestudio),intval($ciclolectivo));
            $subModel = new Instituto();
            
            return $this->render('index', ['model' => $model, 'subModel' => $subModel,'dataProvider' => $dataProvider ]);
        }
        catch (\yii\db\Exception $e) 
        {
            return $this->redirect(['error/db-grant-error',]); 
        }
    }
}