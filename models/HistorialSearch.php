<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historial;

class HistorialSearch extends Historial
{
 
    
    public function rules()
    {
        return [
            [['historial_id', 'usuario_id', 'programa_id','archivoprograma_id'], 'integer'],
            [['archivo','comentario'], 'safe'],
        ];
    }
    
     public function search($params)
    {
        $query = Historial::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'programa_id' => $this->archivoprograma_id,
        ]);

        return $dataProvider;
    }

    
}
