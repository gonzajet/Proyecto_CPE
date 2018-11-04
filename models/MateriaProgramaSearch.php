<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\SqlDataProvider;
use app\models\Instituto;
use app\models\Materia;
use app\models\Carrera;
use app\models\Planestudio;
use app\models\MateriaPrograma;
use app\commands\RoleAccessChecker;

class MateriaProgramaSearch extends  Model
{
    
    
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search()
    {

        $query1 ='';
        $dataProvider='';
        if (Yii::$app->user->identity != null)
        {
            if (RoleAccessChecker::actionIsAsignSector('roladmin'))
            {
                   $query1= "
                    SELECT  planestudio.plan , materia.nombre , ano.ano as planaño, planmateria.planmateria_id, planmateria.programa, estado.descripcion estado  

                    FROM instituto 
                        inner join carrera on instituto.instituto_id = carrera.instituto_id 
                        inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                        inner join ano on planestudio.ano_id = ano.ano_id
                        inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                        inner join materia on planmateria.materia_id = materia.materia_id
                        left join programa on planmateria.planmateria_id = programa.planmateria_id
                        left join archivoprograma on programa.programa_id = archivoprograma.programa_id
                        left join estado on archivoprograma.estado_id = estado.estado_id";
                   
                  $dataProvider = new SqlDataProvider([
                    'sql' => $query1,
                  ]);
            }
            if (RoleAccessChecker::actionIsAsignSector('rolinstituto'))
            {
                $usuario = Yii::$app->user->identity->usuario_id;
                $carreraId = Usuariocarrera::findOne(['usuario_id' => $usuario])->carrera_id; 
                $institutoId = Carrera::findOne(['carrera_id' => $carreraId])->instituto_id;
                
                  $query1= "
                        SELECT  planestudio.plan , materia.nombre , ano.ano as planaño, planmateria.planmateria_id, planmateria.programa, estado.descripcion estado  

                        FROM instituto 
                            inner join carrera on instituto.instituto_id = carrera.instituto_id 
                            inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                            inner join ano on planestudio.ano_id = ano.ano_id
                            inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                            inner join materia on planmateria.materia_id = materia.materia_id
                            left join programa on planmateria.planmateria_id = programa.planmateria_id
                            left join archivoprograma on programa.programa_id = archivoprograma.programa_id
                            left join estado on archivoprograma.estado_id = estado.estado_id
                        WHERE
                            instituto.instituto_id = :institutoId AND
                            carrera.carrera_id = :carreraId";
                  
                  $dataProvider = new SqlDataProvider([
                        'sql' => $query1,
                        'params' => [':institutoId' => $institutoId ,':carreraId' => $carreraId],
                        ]);
        
            }
            if (RoleAccessChecker::actionIsAsignSector('rolprensa'))
            {
                $query1= "
                    SELECT  planestudio.plan , materia.nombre , ano.ano as planaño, planmateria.planmateria_id, planmateria.programa, estado.descripcion estado  

                    FROM instituto 
                        inner join carrera on instituto.instituto_id = carrera.instituto_id 
                        inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                        inner join ano on planestudio.ano_id = ano.ano_id
                        inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                        inner join materia on planmateria.materia_id = materia.materia_id
                        left join programa on planmateria.planmateria_id = programa.planmateria_id
                        left join archivoprograma on programa.programa_id = archivoprograma.programa_id
                        left join estado on archivoprograma.estado_id = estado.estado_id
                    WHERE
                        estado.estado_id is not null AND estado.estado_id = 4 
                        ";
                
                   $dataProvider = new SqlDataProvider([
                    'sql' => $query1,
                  ]);
            }
        }

       
        
   
        return $dataProvider;
    }
    
    
    public function buscarProgramas($institutoId,$carreraId,$planestudioId,$ciclolectivoId)
    {
        $tieneRol = RoleAccessChecker::actionIsAsignSector('roladmin') || RoleAccessChecker::actionIsAsignSector('rolinstituto'); 
        
        if ($tieneRol)
        {
          $query1= "
            SELECT  planestudio.plan , materia.nombre , ano.ano as planaño, planmateria.planmateria_id, planmateria.programa, estado.descripcion estado  

            FROM instituto 
                inner join carrera on instituto.instituto_id = carrera.instituto_id 
                inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                inner join ano on planestudio.ano_id = ano.ano_id
                inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                inner join materia on planmateria.materia_id = materia.materia_id
                left join programa on planmateria.planmateria_id = programa.planmateria_id
                left join archivoprograma on programa.programa_id = archivoprograma.programa_id
                left join estado on archivoprograma.estado_id = estado.estado_id
            WHERE
                instituto.instituto_id = :institutoId AND
                planestudio.planestudio_id  = :planestudioId AND
                carrera.carrera_id = :carreraId AND
                ano.ano_id = :ciclolectivoId";
        

            $dataProvider = new SqlDataProvider([
                'sql' => $query1,
                'params' => [':institutoId' => $institutoId ,':planestudioId' => $planestudioId, ':carreraId' => $carreraId, ':ciclolectivoId' => $ciclolectivoId],
                ]);
        }
        else
        {
          $query1= "
            SELECT  planestudio.plan , materia.nombre , ano.ano as planaño, planmateria.planmateria_id, planmateria.programa, estado.descripcion estado  

            FROM instituto 
                inner join carrera on instituto.instituto_id = carrera.instituto_id 
                inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                inner join ano on planestudio.ano_id = ano.ano_id
                inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                inner join materia on planmateria.materia_id = materia.materia_id
                left join programa on planmateria.planmateria_id = programa.planmateria_id
                left join archivoprograma on programa.programa_id = archivoprograma.programa_id
                left join estado on archivoprograma.estado_id = estado.estado_id
            WHERE
                instituto.instituto_id = :institutoId AND
                planestudio.planestudio_id  = :planestudioId AND
                carrera.carrera_id = :carreraId AND
                ano.ano_id = :ciclolectivoId AND
                estado.estado_id is not null AND estado.estado_id = 4 
                ";
        

            $dataProvider = new SqlDataProvider([
                'sql' => $query1,
                'params' => [':institutoId' => $institutoId ,':planestudioId' => $planestudioId, ':carreraId' => $carreraId, ':ciclolectivoId' => $ciclolectivoId],
                ]);
        }
        
     
       
        return $dataProvider;
    }
    
    
}