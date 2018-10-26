<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\commands;
use yii\console\Controller;

/**
 * Description of Log
 *
 * @author federico
 */
class Log extends Controller{
    
    
    public static function file_force_contents($dir, $contents){
        
        //$path = str_replace("web","",getcwd());
        $parts = explode(getcwd(), $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir);
        file_put_contents("$dir/$file", $contents. "\r\n", FILE_APPEND);
    }
    
}
