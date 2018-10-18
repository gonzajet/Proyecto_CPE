<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;

class MateriaPrograma extends  Model
{
    public $instituto;
    public $carrera;
    public $planestudio;
    public $ciclolectivo;
    public $title;
    
    public function attributeLabels()
    {
        return [
            'instituto' => 'Instituto',
            'carrera' => 'Carrera',
            'planestudio' => 'Plan Estudio',
            'ciclolectivo' => 'Ciclo Lectivo',
        ];
    }

    
    
}