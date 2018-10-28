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


    if (Yii::$app->user->identity != null){
            
        $instituto = Yii::$app->user->identity->getID();
        $query1 = "
                SELECT  planestudio.plan , materia.nombre , ano.ano as anoidmateria , archivoprograma.fecha , estado.descripcion   , archivoprograma.archivo , archivoprograma.archivoprograma_id

                FROM instituto 
                    inner join carrera on instituto.instituto_id = carrera.instituto_id 
                    inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                    inner join ano on planestudio.ano_id = ano.ano_id
                    inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                    inner join programa on planmateria.planmateria_id = programa.planmateria_id
                    inner join materia on planmateria.materia_id = materia.materia_id
                    inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
                    inner join estado on archivoprograma.estado_id = estado.estado_id";
    }else{
         $query1 = "
                SELECT  planestudio.plan , materia.nombre , archivoprograma.fecha , estado.descripcion 
 
                FROM instituto inner join carrera on instituto.instituto_id = carrera.instituto_id 
                    inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                    inner join ano on planestudio.ano_id = ano.ano_id
                    inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                    inner join programa on planmateria.planmateria_id = programa.planmateria_id
                    inner join materia on planmateria.materia_id = materia.materia_id
                    inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
                    inner join estado on archivoprograma.estado_id = estado.estado_id";
    }
        $dataProvider = new SqlDataProvider([
            'sql' => $query1,
            ]);
        
   
        return $dataProvider;
    }
    
    
    public function buscarProgramas($institutoId,$carreraId,$planestudioId,$ciclolectivoId)
    {
        
    
        // if ($carreraId == NULL && $ciclolectivoId == NULL) {
        //     $query1= "
        //     SELECT  planestudio.plan , materia.nombre , archivoprograma.fecha , estado.descripcion 

        //     FROM instituto 
        //         inner join carrera on instituto.instituto_id = carrera.instituto_id 
        //         inner join planestudio on carrera.carrera_id = planestudio.carrera_id
        //         inner join ano on planestudio.ano_id = ano.ano_id
        //         inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
        //         inner join programa on planmateria.planmateria_id = programa.planmateria_id
        //         inner join materia on planmateria.materia_id = materia.materia_id
        //         inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
        //         inner join estado on archivoprograma.estado_id = estado.estado_id
        //     WHERE 
        //         planestudio.planestudio_id  = :planestudioId";

        //     $dataProvider = new SqlDataProvider([
        //         'sql' => $query1,
        //         'params' => [':planestudioId' => $planestudioId],
        //     ]);   
        // }
        // elseif ($carreraId == NULL && $ciclolectivoId != NULL) {
        //        $query1= "
        //         SELECT  planestudio.plan , materia.nombre , archivoprograma.fecha , estado.descripcion 
 
        //         FROM instituto 
        //             inner join carrera on instituto.instituto_id = carrera.instituto_id 
        //             inner join planestudio on carrera.carrera_id = planestudio.carrera_id
        //             inner join ano on planestudio.ano_id = ano.ano_id
        //             inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
        //             inner join programa on planmateria.planmateria_id = programa.planmateria_id
        //             inner join materia on planmateria.materia_id = materia.materia_id
        //             inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
        //             inner join estado on archivoprograma.estado_id = estado.estado_id
        //         WHERE 
        //             planestudio.planestudio_id  = :planestudioId AND
        //             ano.ano_id = :ciclolectivoId";

        //         $dataProvider = new SqlDataProvider([
        //             'sql' => $query1,
        //             'params' => [':planestudioId' => $planestudioId, ':ciclolectivoId' => $ciclolectivoId],
        //             ]);
        // }
        if ($carreraId != NULL && $ciclolectivoId == NULL) {
             $query1= "
                SELECT  planestudio.plan , materia.nombre , ano.ano as anoidmateria ,  archivoprograma.fecha , estado.descripcion  , archivoprograma.archivo , archivoprograma.archivoprograma_id
 
                FROM instituto 
                    inner join carrera on instituto.instituto_id = carrera.instituto_id 
                    inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                    inner join ano on planestudio.ano_id = ano.ano_id
                    inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                    inner join programa on planmateria.planmateria_id = programa.planmateria_id
                    inner join materia on planmateria.materia_id = materia.materia_id
                    inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
                    inner join estado on archivoprograma.estado_id = estado.estado_id
                WHERE 
                    planestudio.planestudio_id  = :planestudioId AND
                    carrera.carrera_id = :carreraId ";

                $dataProvider = new SqlDataProvider([
                    'sql' => $query1,
                    'params' => [':planestudioId' => $planestudioId, ':carreraId' => $carreraId],
                    ]);
        }
        else{


        

        

        $query1= "
            SELECT  planestudio.plan , materia.nombre , ano.ano as anoidmateria , archivoprograma.fecha , estado.descripcion   , archivoprograma.archivo , archivoprograma.archivoprograma_id

            FROM instituto 
                inner join carrera on instituto.instituto_id = carrera.instituto_id 
                inner join planestudio on carrera.carrera_id = planestudio.carrera_id
                inner join ano on planestudio.ano_id = ano.ano_id
                inner join planmateria on planestudio.planestudio_id = planmateria.planestudio_id
                inner join programa on planmateria.planmateria_id = programa.planmateria_id
                inner join materia on planmateria.materia_id = materia.materia_id
                inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
                inner join estado on archivoprograma.estado_id = estado.estado_id
            WHERE 
                planestudio.planestudio_id  = :planestudioId AND
                carrera.carrera_id = :carreraId AND
                ano.ano_id = :ciclolectivoId";
        };

            $dataProvider = new SqlDataProvider([
                'sql' => $query1,
                'params' => [':planestudioId' => $planestudioId, ':carreraId' => $carreraId, ':ciclolectivoId' => $ciclolectivoId],
                ]);
        
        return $dataProvider;
    }
    
    
}