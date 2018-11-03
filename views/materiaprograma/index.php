<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$this->title = 'Reporte CPE';
//$this->params['breadcrumbs'][] = $this->title;
?>


<script type="text/javascript">

   function buscar()
   {
        var validar = validarCombos();
        if (!validar[0])
        {
            $("#ModalCamposObligatorios").modal();
            $(".modal-body").html("<p>" + validar[1] +"</p>");
        }
        else
        {
            var instituto = $("#materiaprograma-instituto").val();
            var carrera = $("#materiaprograma-carrera").val();
            var planestudio = $("#materiaprograma-planestudio").val();
            var ciclolectivo = $("#materiaprograma-ciclolectivo").val();
        
            var query = "&instituto="+instituto + "&carrera=" + carrera +"&planestudio="+planestudio +"&ciclolectivo="+ciclolectivo;
            window.location =  '<?= Url::to(['materiaprograma/get-programas-materias']); ?>' + query;
           
        } 
        
   }
   
   function validarCombos()
   {
       var msg = "";
       var ok = true;
       
        if ($("#materiaprograma-instituto").val() == "")
        {
            msg += "- Instituto<br/>";
            ok = false;
        }
        if ($("#materiaprograma-carrera").val() == "")
        {
            msg += "- Carrera<br/>";
            ok = false;
        }
        if ($("#materiaprograma-planestudio").val() =="")
        {
            msg += "- Plan Estudio<br/>";
            ok = false;
        }
       
        if ($("#materiaprograma-ciclolectivo").val() == "")
        {
            msg += "- Ciclo lectivo<br/>";
            ok = false;
        }
       return [ok,msg];
   }
   
  $(document).ready(function(){
    
    //$("#search").attr("disabled",true);
    $('#materiaprograma-instituto').change(function()
    {
        var url = '<?= Url::to(['materiaprograma/get-carreras']); ?>';
        var instituto = this.value;
        var combo = $('#materiaprograma-carrera');
        var comboplanestudio = $('#materiaprograma-planestudio');
        var comoanio = $('#materiaprograma-ciclolectivo');
        combo.empty().append('<option value="">Please Select</option>');
        comboplanestudio.empty().append('<option value="">Please Select</option>');
        comoanio.empty().append('<option value="">Please Select</option>');
        if (instituto != '')
        {
            $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    data: {institutoId: instituto}, //get model dan ukuran
                    success: function (data) {
                        var htmlOptions = [];
                        if( data.length ){
                            htmlOptions[0] ='<option value="">Please Select</option>'; 
                            for( item in data ) 
                            {
                                html = '<option value="' + data[item].carrera_id + '">' + data[item].descripcion + '</option>';
                                htmlOptions[htmlOptions.length] = html;
                            }
                            // here you will empty the pre-existing data from you selectbox and will append the htmlOption created in the loop result
                            combo.empty().append(htmlOptions.join(''));
                        }
                        //console.log(data);
                    },
                    error: function (e) {
                        //called when there is an error
                        console.log(e.message);
                    }
                });
        }
        
    });
    
    $('#materiaprograma-carrera').change(function()
    {
        var url_plan_estudio = '<?= Url::to(['materiaprograma/get-plan-estudio']); ?>';
        var carrera = this.value;
        var comboplanestudio = $('#materiaprograma-planestudio');
        var comoanio = $('#materiaprograma-ciclolectivo');
        comboplanestudio.empty().append('<option value="">Please Select</option>');
        comoanio.empty().append('<option value="">Please Select</option>');
        if (carrera != '')
        {
            $.ajax({
                    url: url_plan_estudio,
                    type: "POST",
                    dataType: "JSON",
                    data: {carreraId: carrera}, //get model dan ukuran
                    success: function (data) {
                        var htmlOptions = [];
                        if( data.length ){
                            htmlOptions[0] ='<option value="">Please Select</option>';
                            for( item in data ) 
                            {
                                html = '<option value="' + data[item].planestudio_id + '">' + data[item].plan + '</option>';
                                htmlOptions[htmlOptions.length] = html;
                            }
                            // here you will empty the pre-existing data from you selectbox and will append the htmlOption created in the loop result
                            comboplanestudio.empty().append(htmlOptions.join(''));
                        }
                        //console.log(data);
                    },
                    error: function (e) {
                        //called when there is an error
                        console.log(e.message);
                    }
                });
        }
    });
    
    $('#materiaprograma-planestudio').change(function()
    {
        var url_plan_estudio = '<?= Url::to(['materiaprograma/get-ciclo-lectivo']); ?>';
        var planestudio = this.value;
        var comoanio = $('#materiaprograma-ciclolectivo');
        comoanio.empty().append('<option value="">Please Select</option>');
        if (planestudio != '')
        {
            $.ajax({
                    url: url_plan_estudio,
                    type: "POST",
                    dataType: "JSON",
                    data: {}, //get model dan ukuran
                    success: function (data) {
                        var htmlOptions = [];
                        if( data.length ){
                            htmlOptions[0] ='<option value="">Please Select</option>';
                            for( item in data ) 
                            {
                                html = '<option value="' + data[item].ano_id + '">' + data[item].ano + '</option>';
                                htmlOptions[htmlOptions.length] = html;
                            }
                            // here you will empty the pre-existing data from you selectbox and will append the htmlOption created in the loop result
                            comoanio.empty().append(htmlOptions.join(''));
                        }
                    },
                    error: function (e) {
                        //called when there is an error
                        console.log(e.message);
                    }
                });
        }
    });
       
  });
  
  
  
</script> 

<div class="col-lg-8">
<div class="planestudio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, "instituto")
              ->dropDownList(
               ArrayHelper::map($subModel->getAllInstitutos(), 'instituto_id', 'nombre'), ['class'=>'form-control instituto','prompt'=>'Please Select', 'required'=>true])
    ?>
    
    
    <?= $form->field($model, "carrera")
              ->dropDownList(
               ArrayHelper::map([], 'carrera_id', 'descripcion'), ['class'=>'form-control carrera','prompt'=>'Please Select','required'=>true])
    ?>
    
     <?= $form->field($model, "planestudio")
              ->dropDownList(
               ArrayHelper::map([], 'planestudio_id', 'descripcion'), ['class'=>'form-control planestudio','prompt'=>'Please Select','required'=>true])
    ?>
    
    <?= $form->field($model, "ciclolectivo")
              ->dropDownList(
               ArrayHelper::map([], 'ano_id', 'descripcion'), ['class'=>'form-control ciclolectivo','prompt'=>'Please Select','required'=>true])
    ?>


    <p>
        <?= Html::button('Buscar', [ 'class' => 'btn btn-success', 'id' =>'search', 'onclick' => 'buscar();' ]);?>
    </p>
    

    <?php ActiveForm::end(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showOnEmpty'=>true,
        'columns' => [
           ['class' => 'yii\grid\SerialColumn'],
            'anoidmateria',
            [
                'attribute' => 'nombre',
                'value' => function ($model) {
                    return Html::a($model["nombre"],(($model["programa"] == true) ? ['document-upload/historial2', 'planmateriaId' => $model["planmateria_id"]] :('#')));
                },
                'format' => 'raw'
            ],      
            'estado',        
            [
            'header' => '',
            'class' => 'yii\grid\ActionColumn',
            'template' => ' {myButton}',  // the default buttons + your custom button
            'buttons' => [
                'myButton' => function($url, $model, $key ) {     // render your custom button

                    if ($model["programa"]){
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['document-upload/historial', 'id' => $model["planmateria_id"]], ['class'=>'']) ;
                    }
                    else
                    {
                        return Html::a('<i class="glyphicon glyphicon-plus"></i>', ['programa/create', 'planmateriaId' => $model["planmateria_id"]], ['class'=>'']) ;
                    }
                }
            ]
        ],
        ],
        
    ]); ?>
    <!-- planestudio.plan , materia.nombre , archivoprograma.fecha , estado.descripcion  -->

</div>
    
 <div class="modal fade" id="ModalCamposObligatorios" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Campos Obligatorios</h4>
        </div>
        <div class="modal-body" style="color: #F00;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

    
</div>

