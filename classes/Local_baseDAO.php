<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Local_baseDAO
 *
 * @author 301_Kralin_A_V
 */
class Local_baseDAO {
    private function getBaseByResult($result){
        $local_base = new Local_base();
        $local_base->id = $result["id"];
        $local_base->name = $result["name"];
        $local_base->notice = $result["notice"];
        $local_base->id_region = $result["id_region"];
        $local_base->archive = $result["archive"];
        return $local_base;
    }
    
    public function getBaseById($bd, $id){
        $query = "SELECT * FROM `local_base` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $result = $this->getBaseByResult($data[0]);
            return $result;
        }
    }
    
    public function getBaseListByRegion($bd, $id_region){
        $query = "SELECT * FROM `local_base` WHERE `id_region`='".$id_region."' ORDER BY `name` ASC";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getBaseByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function insertBase($bd, $name,  $id_region, $notice){
       $query="INSERT INTO `local_base`(`name`,  `id_region`, `notice`, `archive`) VALUES('".$name."', '".$id_region."', '".$notice."', 0)";
        $bd->queryClean($query); 
    }
    
    function updateBase($bd, $id, $name, $notice, $archive){
        $query="UPDATE `local_base` SET `name`='".$name."', `notice`='".$notice."', `archive`=".$archive." WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function deleteBase($bd, $id){
        $query = "DELETE FROM `local_base` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }    
    
}
