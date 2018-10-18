<?php

namespace app\controllers;

use Yii;
use app\models\Planestudio;
use app\models\PlanestudioSearch;
use app\models\Carrera;
use app\models\Ano;
use app\models\Planmateria;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\commands\RoleAccessChecker;
use app\controllers\ErrorController;
use yii\helpers\VarDumper;

/**
 * PlanestudioController implements the CRUD actions for Planestudio model.
 */
class PlanestudioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * Lists all Planestudio models.
     * @return mixed
     */
    public function actionIndex(){
		$msg='';
			try{
				$searchModel = new PlanestudioSearch();
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

				return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
				]);
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
   }

    /**
     * Displays a single Planestudio model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
		$msg='';
			try{
				return $this->render('view', [
					'model' => $this->findModel($id),

				]);
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Creates a new Planestudio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
		$msg='';
			try{
				$model = new Planestudio();
				$subModel= new Carrera();
				$subModel2= new Ano();
				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					return $this->redirect(['index', 'id' => $model->planestudio_id]);
				} else {
					return $this->render('create', [
						  'model' => $model,
						'subModel'=> $subModel,
						 'subModel2'=> $subModel2,
					]);
				}
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Updates an existing Planestudio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
		$msg='';
			try{
				$model = $this->findModel($id);
				$subModel= new Carrera();
				$subModel2= new Ano();

				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					return $this->redirect(['index', 'id' => $model->planestudio_id]);
				} else {
					return $this->render('update', [
						'model' => $model,
						'subModel'=> $subModel,
						'subModel2'=> $subModel2,
					]);
				}
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Deletes an existing Planestudio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
	$msg='';


    try{
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            } catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}

            // hay que borrar los el plan materia y los programas asociandos al plan de estudio
            //Planmateria::deleteAll('planestudio_id ='.$id);
            //$this->findModel($id)->delete();
            //return $this->redirect(['index']);
       
    }

    /**
     * Finds the Planestudio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Planestudio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
		if (($model = Planestudio::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
    }
}
