<?php
require_once 'Local_requestion.php';
require_once 'BD.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Local_requestionDAO
 *
 * @author 301_Kralin_A_V
 */
class Local_requestionDAO {
    private function getRequestionByResult($result){
        $localRequestion = new Local_requestion();
        $localRequestion->id = $result['id'];
        $localRequestion->id_user = $result['id_user'];
        $localRequestion->id_local_base = $result['id_local_base'];
        $localRequestion->date_from = $result['date_from'];
        $localRequestion->date_to = $result['date_to'];
        $localRequestion->date_do = $result['date_do'];
        $localRequestion->state = $result["state"];
        $localRequestion->notice = $result['notice'];
        $localRequestion->number = $result['number'];
        $localRequestion->login = $result['login'];
        return $localRequestion;
    }
    
    public function getRequestionById($bd, $id){
        $query = "SELECT * FROM `local_requestion` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $result = $this->getRequestionByResult($data[0]);
            return $result;
        }
    }
    
    public function getReqestionListByImns($bd, $id_imns){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`notice`, r.`login`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` WHERE u.`id_imns`='".$id_imns."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    public function getReqestionListByImnsBaseState($bd, $id_imns, $id_local_base, $state){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`notice`, r.`login`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` WHERE u.`id_imns`='".$id_imns."' AND r.`id_local_base`='".$id_local_base."' AND r.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    
    public function getReqestionListByImnsBaseStateActive($bd, $id_imns, $id_local_base){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`notice`, r.`login`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` WHERE u.`id_imns`='".$id_imns."' AND r.`id_local_base`='".$id_local_base."' AND r.`state`!='прекращен'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    public function getReqestionListByRegion($bd, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`notice`, r.`login`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` JOIN `imns` i ON u.`id_imns`=i.`id` WHERE i.`id_region`='".$id_region."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    public function getReqestionListByRegionBaseState($bd, $id_region, $id_local_base, $state){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`login`, r.`notice`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` JOIN `imns` i ON u.`id_imns`=i.`id` WHERE i.`id_region`='".$id_region."' AND r.`id_local_base`='".$id_local_base."' AND r.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    public function getReqestionListByRegionBaseStateActive($bd, $id_region, $id_local_base){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_local_base`, r.`date_from`, r.`date_to`, r.`date_do`, r.`state`, r.`notice`, r.`login`, r.`number` FROM `local_requestion` r JOIN `users` u ON r.`id_user`=u.`id` JOIN `imns` i ON u.`id_imns`=i.`id` WHERE i.`id_region`='".$id_region."' AND r.`id_local_base`='".$id_local_base."' AND r.`state`!='прекращен'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    public function getReqestionByUserBase($bd, $id_user, $id_base){
        $query = "SELECT * FROM `local_requestion` WHERE `id_user`='".$id_user."' AND `id_local_base`='".$id_base."'";
        $data = $bd->query($query);
        if($data != null){
            $result = $this->getRequestionByResult($data[0]);
            return $result;
        }
    }
    
    public function getReqestionByUser($bd, $id_user){
        $query = "SELECT * FROM `local_requestion` WHERE `id_user`='".$id_user."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
            
    function insert($bd, $id_user, $id_local_base, $date_from, $date_to, $date_do, $state, $number, $notice, $login){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $date_do = checkDateSQL($date_do);
        $query = "INSERT INTO `local_requestion`(`id_user`,`id_local_base`,`date_from`,`date_to`,`date_do`,`state`,`number`,`notice`, `login`) VALUES('".$id_user."','".$id_local_base."',".$date_from.",".$date_to.",".$date_do.",'".$state."','".$number."','".$notice."', '".$login."')";
        $bd->queryClean($query);
    }
    
    function update($bd, $id, $date_from, $date_to, $date_do, $state, $number, $notice, $login){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $date_do = checkDateSQL($date_do);
        $query = "UPDATE `local_requestion` SET `date_from`=".$date_from.", `date_to`=".$date_to.", `date_do`=".$date_do.", `state`='".$state."', `number`='".$number."', `notice`='".$notice."', `login`='".$login."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function updateState($bd, $id, $state){
        $query = "UPDATE `local_requestion` SET `state`='".$state."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function deleteFild($bd, $id){
        $query = "DELETE FROM `local_requestion` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    
    
}
