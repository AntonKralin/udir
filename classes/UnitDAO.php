<?php
require_once 'Unit.php';
require_once 'BD.php';

/**
 * Description of UnitDAO
 *
 * @author 301_Kralin_A_V
 */
class UnitDAO {
    
    private function getUnitByResult($result){
        $unit = new Unit();
        $unit->id = $result['id'];
        $unit->name = $result['name'];
        $unit->id_region = $result['id_region'];
        return $unit;
    }
    
    function  getUnitById($bd, $id){
        $query = "SELECT * FROM `unit` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data!=null){
            $unit = $this->getUnitByResult($data[0]);
            return $unit;
        }
    }

    function getUnitList($bd, $id_region){
        $query = "SELECT * FROM `unit` WHERE `id_region`='".$id_region."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $unit = $this->getUnitByResult($data[$i]);
            $array[$i] = $unit;
        }
        return $array;
    }
    
    function updateFild($bd, $id, $name){
        $query = "UPDATE `unit` SET `name`='".$name."' WHERE `id`=".$id;
        $bd->queryClean($query);
        #return $result;
    }
    
    function  insertFild($bd, $name, $id_region){
        $query = "INSERT INTO `unit`(`name`, `id_region`) VALUES('".$name."','".$id_region."');";
        $bd->queryClean($query);
        #return $result;        
    }
    
    function deleteFild($bd, $id){
        $query = "DELETE FROM `unit` WHERE `id`=".$id;
        $bd->queryClean($query);
    }
}
