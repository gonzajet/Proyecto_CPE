<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Materia;
use app\models\Planestudio;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlanemateriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan de materias';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-8">
<div class="planmateria-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'planmateria_id',
      
            [
              'label' => 'Plan',
              'attribute' => 'plan',
              'value' => function($model){
                $plans=Planestudio::find()->where(['planestudio_id'=>$model->planestudio_id])->one();
                return $plans->plan;
              }
            ],
            
            [
              'label' => 'Materia',
              'attribute' => 'materia_id',
              'value' => function($model){
                $materia=Materia::find()->where(['materia_id'=>$model->materia_id])->one();
                return $materia->nombre;
              }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
