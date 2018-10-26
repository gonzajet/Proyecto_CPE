<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;
use Yii;
use \app\models\Historial;

/**
 * Description of HistorialController
 *
 * @author federico
 */
class HistorialController {
    //put your code here
    
    
    
    
    protected function findModel($id)
    {
        if (($model = Historial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}
