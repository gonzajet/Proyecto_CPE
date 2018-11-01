<?php

namespace app\models;

use Yii;
use app\commands\RegisterModeChecker;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\SqlDataProvider;

/*

 * @property integer $usuario_id
 * @property integer $programa_id
 * @property integer $archivoprograma_id
 * @property integer $archivo
 * @property integer $comentario
 *  */
class Historial extends \yii\db\ActiveRecord
{
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historial';
    }
    
    
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario_id'], 'integer'],
            [['programa_id'], 'integer'],
            [['archivoprograma_id'], 'integer'],
            [['archivo'], 'string', 'max' => 255],
            [['comentario'], 'string', 'max' => 255],
        ];
    }
    
     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'historial_id' => 'Historial',
            'usuario_id' => 'Usuario',
            'programa_id' => 'Programa',
            'archivoprograma_id' => 'ArchivoPrograma',
            'archivo' => 'Archivo',
            'cometario' => 'Comentario',
        ];
    }
    
    
   public function searchPorProgramaId($programaId)
   {
             $query1= "
                SELECT  programa.descripcion programa, archivoprograma.fecha fecha, estado.descripcion estado, archivoprograma.archivo, archivoprograma.archivoprograma_id,
                        historial.usuario_id, comentario 
 
                FROM historial 
                    inner join programa on historial.programa_id = programa.programa_id
                    inner join planmateria on programa.planmateria_id = planmateria.planmateria_id
                    inner join archivoprograma on programa.programa_id = archivoprograma.programa_id
                    inner join estado on archivoprograma.estado_id = estado.estado_id
                    
                WHERE 
                    historial.programa_id  = :programaId";

                $dataProvider = new SqlDataProvider([
                    'sql' => $query1,
                    'params' => [':programaId' => $programaId],
               ]);
                
                return $dataProvider;
        
   }
    
}