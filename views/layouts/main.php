<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* footer: Yii::powered() */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\commands\RoleAccessChecker;
use app\assets\AppAsset;
use app\models\Sector;
use kartik\sidenav\SideNav;
use kartik\widgets\SideNavs;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <div class="page-header" style='margin:20px;text-align:center;'>
        <strong><h3>SISTEMA DE GESTIÓN DE PROGRAMAS DE MATERIAS</h3></strong>        
    </div>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>




<div class="wrap">
    <div class="row">
        
        <div class="col-md-2">
            
               <?php echo Nav::widget(RoleAccessChecker::navWidgetContent()); ?>
            
        </div>

        <div class="col-md-1">
            
        </div>
        <div class="col-md-8 panel panel-default">
            <div class="panel-body">
                <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
                <?= $content ?>
            </div>
        </div>
        
        <div class="col-md-1">
            
        </div>
    </div>

</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">

          <img src="img/unaj.png" style="display:inline; margin-top: -20px; align: center; width:150px; height:55px;">&nbsp&nbsp&nbsp&nbsp<b style="size:15px"></b>
          &copy; UNAJ-CPE <?= date('Y') ?>
        </p>
        <p class="pull-right"> desarrolladores.unajcpe@gmail.com <?= date('') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
