<?php

namespace app\controllers;

use Yii;
use app\models\Programa;
use app\models\ProgramaSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\commands\RoleAccessChecker;
use app\controllers\ErrorController;
use app\models\Ano;
use app\models\Planmateria;


/**
 * ProgramaController implements the CRUD actions for Programa model.
 */
class ProgramaController extends Controller
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
     * Lists all Programa models.
     * @return mixed
     */
    public function actionIndex(){
		$msg='';
			try{
				$searchModel = new ProgramaSearch();
				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

				return $this->render('index', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
				]);
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Displays a single Programa model.
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
     * Creates a new Programa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($planmateriaId){
		$msg='';
			try{
				$model = new Programa();
				$subModel = new Ano();
                                
				if ($model->load(Yii::$app->request->post()) && $model->save()) {
                                        $plan = Planmateria::findOne($model->planmateria_id);  
                                        $plan->programa = true;
                                        $plan->save();
					return $this->redirect(['index', 'id' => $model->programa_id]);
				} else {
                                        $model->planmateria_id = $planmateriaId;
					return $this->render('create', [
						'model' => $model,
			  'subModel' => $subModel,
					]);
				}
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Updates an existing Programa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
		$msg='';
			try{
				$model = $this->findModel($id);
				$subModel = new Ano();

				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					return $this->redirect(['index', 'id' => $model->programa_id]);
				} else {
					return $this->render('update', [
						'model' => $model,
            'subModel' => $subModel,
					]);
				}
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Deletes an existing Programa model.
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
    }

    public function actionHistorial($id){
		$msg='';
			try{
				return $this->render('historial', [
					'model' => $this->findModel($id),
				]);
 			} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

    /**
     * Finds the Programa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Programa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Programa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
