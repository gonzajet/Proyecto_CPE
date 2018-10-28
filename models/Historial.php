<?php

namespace app\models;

use Yii;
use app\commands\RegisterModeChecker;

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

    
}