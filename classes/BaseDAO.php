<?php
require_once 'BD.php';
require_once 'Base.php';

/**
 * Description of BaseDAO
 *
 * @author 301_Kralin_A_V
 */
class BaseDAO {
    private function getBaseByResult($result){
        $base = new Base();
        $base->id = $result['id'];
        $base->name = $result['name'];
        $base->shot_name = $result['shot_name'];
        $base->id_region = $result['id_region'];
        $base->new_login = $result['new_login'];
        $base->notice = $result['notice'];
        $base->archive = $result['archive'];
        return $base;
    }
    
    function getBaseById($bd, $id){
        $query = "SELECT * FROM `base` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $base = $this->getBaseByResult($data[0]);
            return $base;
        }
    }
    
    function getBaseList($bd, $id_region){
        $query = "SELECT * FROM `base` WHERE `id_region`='".$id_region."' ORDER BY `shot_name` ASC";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getBaseByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function insertBase($bd, $name, $shot, $id_region, $new_login, $notice){
       $query="INSERT INTO `base`(`name`, `shot_name`, `id_region`, `new_login`, `notice`, `archive`) VALUES('".$name."','".$shot."','".$id_region."', '".$new_login."', '".$notice."', 0)";
        $bd->queryClean($query); 
    }
    
    function updateBase($bd, $id, $name, $shot, $new_login, $notice, $archive){
        $query="UPDATE `base` SET `name`='".$name."', `shot_name`='".$shot."', `new_login`='".$new_login."', `notice`='".$notice."', `archive`=".$archive." WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function deleteBase($bd, $id){
        $query = "DELETE FROM `base` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
}
