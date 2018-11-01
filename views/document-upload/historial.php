<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Moderw;
use app\models\Usuario;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentUploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-upload-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<!--
    <p><?= Html::a('Crear Document Upload', ['create'], ['class' => 'btn btn-success']) ?></p>
-->
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'programa',
             [
                'label' => 'Usuario',
		'value' => function ($data) {
                      $user = Usuario::find()->where(['usuario_id'=>$data["usuario_id"]])->one();   
                      return $user->nombre ." ".$user->apellido;
                },
                 
             ],
            'estado',
            'comentario',
            'archivo',
            'fecha',
            [
                    'label' => 'Enlaces',
                    'format' => 'raw',
                    'value' => function ($data) {
                            return Html::a('Descargar', 'uploads/'.$data["archivo"]);
                    },
            ],
		
            ['class' => 'yii\grid\ActionColumn', 'template' => ''],
        ],
    ]); ?>
</div>
