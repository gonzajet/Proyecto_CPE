<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\SqlDataProvider;
use yii\data\Pagination;
use yii\web\Response;
use yii\filters\VerbFilter;

use app\models\MateriaProgramaSearch;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Usuario;
use app\models\Usuariocarrera;
use app\models\Carrera;
use app\models\RegisterForm;
use app\models\Sector;
use app\models\Estado;
use app\models\Instituto;
use app\models\Usuariotipo;
use app\models\InvalidoUsuarioModel;
use app\models\Asignsector;
use app\models\BusquedaPrincipalForm;

use app\commands\Mailto;
use app\commands\Intranet;
use app\commands\RandKey;
use app\commands\RoleAccessChecker;
use app\controllers\ErrorController;




class SiteController extends Controller{
    /**
     * @inheritdoc
     */
     public function behaviors() {
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'only' => ['logout', 'contact', 'register', 'login'],
                 'rules' => [
                     [
                         'allow' => true,
                         'actions' => ['login', 'logout', 'contact', 'register',],
                         'roles' => ['?'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['logout', 'register',],
                         'roles' => ['@'],
                     ],
                     [
                         'allow' => false,
                         'actions' => ['contact', 'login'],
                         'roles' => ['@'],
                     ],
                 ],
             ],
             'verbs' => [
                 'class' => VerbFilter::className(),
                 'actions' => [
                     'logout' => ['post'],
                 ],
             ],
         ];
     }
	 	
    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }








 public function actionIndex(){
    try{
		$numUsr=Usuario::find()->count();
		$instituto = "";
		$carrera = "";
		$countEstadosFaltantesPorIns = 0;
		$countEstadosEntregadoPorIns = 0;

		if(Yii::$app->user->isGuest) 
                {
                    $msg='No logoneado...'; 
		} 
		else 
		{
                    $msg='Logoneado!';
		}


		if (($numUsr==0)||(RoleAccessChecker::actionIsAsignSector('site/indexUserAdmCPE'))) 
		{

                    $model = new BusquedaPrincipalForm;

    

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    // validar los datos recibidos en el modelo

                    // aquí haz algo significativo con el modelo ...

                    return $this->render('entry-confirm', ['model' => $model]);
                } else {
                    // la página es mostrada inicialmente o hay algún error de validación
                    $totalCount= Yii::$app->db->createCommand('SELECT  instituto.nombre  , carrera.descripcion , planestudio.plan, ano.ano , planmateria.materia_id , programa.descripcion , materia.nombre FROM instituto inner join carrera on instituto.instituto_id = carrera.instituto_id  inner join planestudio on carrera.carrera_id = planestudio.carrera_id  inner join ano on planestudio.ano_id = ano.ano_id  inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id  inner join programa on planmateria.planmateria_id = programa.planmateria_id  inner join materia on planmateria.materia_id = materia.materia_id ')->queryScalar();

                    $sql = 'SELECT  instituto.nombre  , carrera.descripcion , planestudio.plan, ano.ano , planmateria.materia_id , programa.descripcion , materia.nombre FROM instituto inner join carrera on instituto.instituto_id = carrera.instituto_id  inner join planestudio on carrera.carrera_id = planestudio.carrera_id  inner join ano on planestudio.ano_id = ano.ano_id  inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id  inner join programa on planmateria.planmateria_id = programa.planmateria_id  inner join materia on planmateria.materia_id = materia.materia_id';

                    $sqlProvider = new SqlDataProvider([
                    'sql' => $sql,
                    ]);

                    return $this->render('entry', ['model' => $model , 'sqlProvider' => $sqlProvider]);
                }
                // $estadosFaltantesIngyAgr = Estado::getFaltantes("Instituto de Ingeniería y Agronomía");
                // $countEstadosFaltantesIngyAgr = count ($estadosFaltantesIngyAgr);
                // $estadosEntregadosIngyAgr = Estado::getEntregados("Instituto de Ingeniería y Agronomía");
                // $countEstadosEntregadoIngyAgr = count ($estadosEntregadosIngyAgr);
                // $estadosFaltantesEIni= Estado::getFaltantesIniciales();
                // $countEstadosFaltantesEIni  = count ($estadosFaltantesEIni );
                // $estadosEntregadosEIni = Estado::getEntregadosIniciales();
                // $countEstadosEntregadoEIni  = count ($estadosEntregadosEIni);
                // $estadosFaltantesSalud= Estado::getFaltantes("Instituto de Ciencias de la Salud");
                // $countEstadosFaltantesSalud  = count ($estadosFaltantesSalud );
                // $estadosEntregadosSalud = Estado::getEntregados("Instituto de Ciencias de la Salud");
                // $countEstadosEntregadoSalud  = count ($estadosEntregadosSalud);
                // $estadosFaltantesSocyAdm= Estado::getFaltantes("Instituto de Ciencias Sociales y Administración");
                // $countEstadosFaltantesSocyAdm = count ($estadosFaltantesSocyAdm );
                // $estadosEntregadosSocyAdm = Estado::getEntregados("Instituto de Ciencias Sociales y Administración");
                // $countEstadosEntregadoSocyAdm  = count ($estadosEntregadosSocyAdm);

                // return $this->render
                // ('indexUserAdmCPE',
                // 	[
                // 	'countFaltantesIngyAgr' => $countEstadosFaltantesIngyAgr,
                // 	'countEntregadosIngyAgr' => $countEstadosEntregadoIngyAgr,
                // 	'countFaltantesEIni' => $countEstadosFaltantesEIni,
                // 	'countEntregadosEIni' => $countEstadosEntregadoEIni,
                // 	'countFaltantesSalud' => $countEstadosFaltantesSalud,
                // 	'countEntregadosSalud' => $countEstadosEntregadoSalud,
                // 	'countFaltantesSocyAdm' => $countEstadosFaltantesSocyAdm,
                // 	'countEntregadosSocyAdm' => $countEstadosEntregadoSocyAdm
                // 	]
                // );
		}
		
		else if (Yii::$app->user->identity != null) 
                {
                    
                    $instituto = Yii::$app->user->identity->getSector()->one()->descripcion;
                    $carrera_id = Yii::$app->user->identity->getUsuariocarreras()->one();
                    if ($carrera_id)
                            {
                                    $carreraid = $carrera_id->carrera_id;
                                    $carrera = Carrera::find()->where("carrera_id=:carrera_id", [":carrera_id" => $carreraid])->one()->descripcion;
                                    $instituto_id = Carrera::find()->where("carrera_id=:carrera_id", [":carrera_id" => $carreraid])->one()->instituto_id;
                                    $instituto = Instituto::find()->where("instituto_id=:instituto_id", [":instituto_id" => $instituto_id])->one()->nombre;
                                    $estadosFaltantesPorIns = Estado::getFaltantesPorInst($instituto,$carrera);
                                    $countEstadosFaltantesPorIns = count ($estadosFaltantesPorIns);
                                    $estadosEntregadosPorIns = Estado::getEntregadosPorInst($instituto,$carrera);
                                    $countEstadosEntregadoPorIns = count ($estadosEntregadosPorIns);	
                            }
                    else
                            {
                                    $estadosFaltantesPorIns = Estado::getFaltantesIniciales();
                                    $countEstadosFaltantesPorIns = count ($estadosFaltantesPorIns);
                                    $estadosEntregadosPorIns = Estado::getEntregadosIniciales();
                                    $countEstadosEntregadoPorIns = count ($estadosEntregadosPorIns);	
                            }

                    return $this->render
                            (
                                    'index',
                                    [
                                            'instituto'=>$instituto,
                                            'carrera'=>$carrera,
                                            'countEstadosFaltantesPorIns' => $countEstadosFaltantesPorIns,
                                            'countEstadosEntregadoPorIns' => $countEstadosEntregadoPorIns
                                    ]
                            ); 
                }
		else
		{

                    return $this->render
                            ('index',
                                    [
                                            'carrera'=>$carrera,
                                            'instituto'=>$instituto,	
                                            'countEstadosFaltantesPorIns' => $countEstadosFaltantesPorIns,
                                            'countEstadosEntregadoPorIns' => $countEstadosEntregadoPorIns
                                    ]
                            );
		}
		
		} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
   }
	
  
    public function actionLogin(){
        try{
			if (!Yii::$app->user->isGuest) return $this->goHome();

			$model = new LoginForm();
			if ($model->load(Yii::$app->request->post()) && $model->login()) return $this->goBack();
			return $this->render('login', ['model' => $model, ]);
 		} catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

 
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();
    }


    public function actionContact(){
        try{
			$model = new ContactForm();
			if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('contactFormSubmitted');

				return $this->refresh();
			}
			return $this->render('contact', [
				'model' => $model,
			]);
        } catch (\yii\db\Exception $e) {return $this->redirect(['error/db-grant-error',]);}
    }

 
    public function actionAbout(){
        return $this->render('about');
    }


    public function actionRegister() {
 
			$numUsr=Usuario::find()->count();
		
				$model = new RegisterForm();
				$ref=new Sector();

				//habilita opcion CPE Admin si no hay usuarios
				if ($numUsr == 0){
					$subModel=$ref->find()->where('sector_id=:sector_id',[':sector_id'=> 1]);
				} 	
				else {
					$subModel=$ref->find()->where('sector_id>:sector_id',[':sector_id'=>1]);
				}
					
				$msg = "Cantidad de usuarios= ". $numUsr;
				
				if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ($model->load(Yii::$app->request->post())) {
					
					if ($model->validate()) {
						
						$table = new Usuario();
						$this->fillModelUsuario($table, $model);
						
						if ($table->insert()) 
						{
							$sector = Sector::find()->where("sector_id=:sector_id", [":sector_id" => $model->sector_id])->one()->descripcion;
							$this->setUsuarioCarrera($sector, $table->usuario_id);
							$this->nullModelRegister($model);
							?>
								<div class="alert alert-success">
									<a href="#" class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
									<h4><strong>¡Usuario registrado!</strong></h4>
								</div>
							<?php
							echo "<meta http-equiv='refresh' content='8; " . Url::toRoute("site/login") . "'>";

						} 
						else
						{
							$msg = "Ha ocurrido un error al llevar a cabo tu  registro\n";/* error causado porque falla la base de datos */
						}
					} 
					else
					{ 
						$model->getErrors();/* error detectado por la validacion del formulario */
					}
				}
				return $this->render("register", ["model" => $model,"subModel" => $subModel, "msg" => $msg]);/* Sin errores detectados */
    }
    
    //
    private function setUsuarioCarrera($sector, $usuario_id){
		$carrera = explode("- ", trim($sector, "- "));
		unset($carrera[0]); 
		$final_carrera = implode($carrera);
		$carrera_id = Carrera::find()->where("descripcion=:descripcion", [":descripcion" => $final_carrera])->one();
		if ($carrera_id){
			$carreraid = $carrera_id->carrera_id;
			$table = new Usuariocarrera();
			$table->carrera_id = $carreraid;
			$table->usuario_id = $usuario_id;
			$table->insert();
		}
	}

	//BORRA CAMPOS DEL FORMULARIO
	private function nullModelRegister($model){
		$model->Nombre = null;
		$model->Apellido = null;
		$model->email = null;
		$model->password = null;
		$model->password_repeat = null;
    }
 	//COMPLETA EL MODELO DE LA BD
    private function fillModelUsuario($src,$src2){
		$src->loadDefaultValues();
		$src->sector_id = $src2->sector_id;
		$src->nombre = $src2->Nombre;
		$src->apellido = $src2->Apellido;
		$src->passworduser = crypt($src2->password, Yii::$app->params["salt"]);
		$src->mailuser = $src2->email;
		$src->authkeyuser = RandKey::randKey("abcdef0123456789", 200);
		$src->activuser = 1;
    }
	


}
