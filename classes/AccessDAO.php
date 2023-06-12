<?php
require_once 'Access.php';
require_once 'BD.php';
/**
 * Description of AccessDAO
 *
 * @author 301_Kralin_A_V
 */
class AccessDAO {
    
    private function getAccessByResult($result){
        $access = new Access();
        $access->id = $result['id'];
        $access->name = $result['name'];
        return $access;
    }
    
    function getAccessList($bd){
        $query = "SELECT * FROM `access`";
        $result = $bd->query($query);
        $array = [];
        for($i=0; $i<count($result); $i++){
            $array[$i] = $this->getAccessByResult($result[$i]);
        }
        return $array;
    }
    
    
}
