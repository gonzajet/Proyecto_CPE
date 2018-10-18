<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'instituto') ?>

    <?= $form->field($model, 'carrera') ?>

    <?= $form->field($model, 'ano') ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>


<hr>


<?php ActiveForm::end(); ?>

<?= GridView::widget([
	'dataProvider' => $sqlProvider,
	'columns' => [
		'nombre',
		'plan',
		'ano',
		[
       'label' => 'Debug',
       'value' => function ($model) {
           return serialize($model);
       }]
			]


]);