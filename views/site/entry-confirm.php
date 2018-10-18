<?php
use yii\helpers\Html;
?>
<p>You have entered the following information:</p>

<ul>
    <li><label>Instituto</label>: <?= Html::encode($model->instituto) ?></li>
    <li><label>Carrera</label>: <?= Html::encode($model->carrera) ?></li>
	<li><label>Año</label>: <?= Html::encode($model->ano) ?></li>

</ul>