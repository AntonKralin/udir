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
class Local_avtoLineDAO {
    private function getLocalAvtoLineByResualt($resualt){
        $local_avtoLine = new Local_avtoLine();
        $local_avtoLine->id = $resualt["id"];
        $local_avtoLine->type = $resualt["type"];
        $local_avtoLine->yourname=$resualt["yourname"];
        $local_avtoLine->name = $resualt["name"];
        $local_avtoLine->date = $resualt["date"];
        $local_avtoLine->id_base = $resualt["id_base"];
        return $local_avtoLine;
    }
    
    public function getLocalAvtoLineById($bd, $id) {
        $query = "SELECT * FROM `local_avtoline` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $local_avtoLine = $this->getLocalAvtoLineByResualt($data[0]);
            return $local_avtoLine;
        }
    }
    
    public function getLocalAvtoLineByBase($bd, $id_base){
        $query = "SELECT * FROM `local_avtoline` WHERE `id_base`='".$id_base."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $local_avtoLine = $this->getLocalAvtoLineByResualt($data[$i]);
            $array[$i]=$local_avtoLine;            
        }
        return $array;
    }
    
    public function getLocalAvtoLineTypeByBase($bd, $id_base){
        $query = "SELECT DISTINCT `type` FROM `local_avtoline` where `id_base`='".$id_base."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $array[$i]=$data[$i]['type'];            
        }
        return $array;
    }
    
    public function getLocalAvtoLineByBaseType($bd, $id_base, $type){
        $query = "SELECT * FROM `local_avtoline` WHERE `id_base`='".$id_base."' AND `type`='".$type."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $avtoLine = $this->getLocalAvtoLineByResualt($data[$i]);
            $array[$i]=$avtoLine;            
        }
        return $array;
    }
    
    public function insertLocalAvtoLine($bd, $type, $yourname, $name, $date, $id_base){
        $query = "INSERT INTO `local_avtoline`(`type`, `yourname`,`name`,`date`,`id_base`) VALUES('".$type."','".$yourname."','".$name."','".$date."','".$id_base."')";
        $bd->queryClean($query);
    }
    
    public function deleteLocalAvtoLine($bd, $id){
        $query = "DELETE FROM `local_avtoline` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    public function deleteLocalAvtoLineBase($bd, $id_base){
        $query = "DELETE FROM `local_avtoline` WHERE `id_base`='".$id_base."'";
        $bd->queryClean($query);
    }
    
}
