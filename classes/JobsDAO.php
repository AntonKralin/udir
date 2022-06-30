<?php
require_once 'Jobs.php';
require_once 'BD.php';

/**
 * Description of JobsDAO
 *
 * @author 301_Kralin_A_V
 */
class JobsDAO {
    
    private function getJobsByResult($result){
        $jobs = new Jobs();
        $jobs->id = $result['id'];
        $jobs->name = $result['name'];
        $jobs->id_region = $result['id_region'];
        return $jobs;
    }
    
    function getJobsById($bd, $id){
        $query = "SELECT * FROM `jobs` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if ($data != null){
            $jobs = $this->getJobsByResult($data[0]);
            return $jobs;
        }
    }
    
    function getJobsList($bd, $id_region){
        $query = "SELECT * FROM `jobs` WHERE `id_region`='".$id_region."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $jobs = $this->getJobsByResult($data[$i]);
            $array[$i] = $jobs;
        }
        return $array;
    }
    
    function updateFild($bd, $id, $name){
        $query = "UPDATE `jobs` SET `name`='".$name."' WHERE `id`=".$id;
        $bd->queryClean($query);
        #return $result;
    }
    
    function  insertFild($bd, $name, $id_region){
        $query = "INSERT INTO `jobs`(`name`, `id_region`) VALUES('".$name."','".$id_region."');";
        $bd->queryClean($query);
        #return $result;        
    }
    
    function deleteFild($bd, $id){
        $query = "DELETE FROM `jobs` WHERE `id`=".$id;
        $bd->queryClean($query);
    }
}
