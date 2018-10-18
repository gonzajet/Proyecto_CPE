<?php

namespace app\models;

use yii\base\Model;

class BusquedaPrincipalForm extends Model
{
    public $instituto;
    public $carrera;
    public $ano;

    public function rules()
    {
        return [
            [['instituto', 'carrera', 'ano'], 'required']
        ];
    }
}