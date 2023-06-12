<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AvtoLineDAO
 *
 * @author 301_Kralin_A_V
 */
class AvtoLineDAO {
    private function getAvtoLineByResualt($resualt){
        $avtoLine = new AvtoLine();
        $avtoLine->id = $resualt["id"];
        $avtoLine->type = $resualt["type"];
        $avtoLine->yourname=$resualt["yourname"];
        $avtoLine->name = $resualt["name"];
        $avtoLine->date = $resualt["date"];
        $avtoLine->id_base = $resualt["id_base"];
        return $avtoLine;
    }
    
    public function getAvtoLineById($bd, $id) {
        $query = "SELECT * FROM `avtoline` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $avtoLine = $this->getAvtoLineByResualt($data[0]);
            return $avtoLine;
        }
    }
    
    public function getAvtoLineByBase($bd, $id_base){
        $query = "SELECT * FROM `avtoline` WHERE `id_base`='".$id_base."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $avtoLine = $this->getAvtoLineByResualt($data[$i]);
            $array[$i]=$avtoLine;            
        }
        return $array;
    }
    
    public function getAvtoLineTypeByBase($bd, $id_base){
        $query = "SELECT DISTINCT `type` FROM `avtoline` where `id_base`='".$id_base."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $array[$i]=$data[$i]['type'];            
        }
        return $array;
    }
    
    public function getAvtoLineByBaseType($bd, $id_base, $type){
        $query = "SELECT * FROM `avtoline` WHERE `id_base`='".$id_base."' AND `type`='".$type."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $avtoLine = $this->getAvtoLineByResualt($data[$i]);
            $array[$i]=$avtoLine;            
        }
        return $array;
    }
    
    public function insertAvtoLine($bd, $type, $yourname, $name, $date, $id_base){
        $query = "INSERT INTO `avtoline`(`type`, `yourname`,`name`,`date`,`id_base`) VALUES('".$type."','".$yourname."','".$name."','".$date."','".$id_base."')";
        $bd->queryClean($query);
    }
    
    public function deleteAvtoLine($bd, $id){
        $query = "DELETE FROM `avtoline` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    public function deleteAvtoLineBase($bd, $id_base){
        $query = "DELETE FROM `avtoline` WHERE `id_base`='".$id_base."'";
        $bd->queryClean($query);
    }
    
}
