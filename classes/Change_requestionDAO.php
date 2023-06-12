<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Change_requestionDAO
 *
 * @author 301_Kralin_A_V
 */
class Change_requestionDAO {
    private function getRequestionByResult($result){
        $changeRequestion = new Change_requestion();
        $changeRequestion->id = $result['id'];
        $changeRequestion->id_user = $result['id_user'];
        $changeRequestion->id_base = $result['id_base'];
        $changeRequestion->id_admins = $result['id_admins'];
        $changeRequestion->filds = $result['filds'];
        $changeRequestion->notice = $result['notice'];
        $changeRequestion->date_change = $result['date_change'];
        return $changeRequestion;
    }
    
    public function getRequestionById($bd, $id){
        $query = "SELECT * FROM `change_requestion` WHERE `id`='".$id."'";
        $resual = $bd->query($query);
        if ($resual != null){
            $users = $this->getRequestionByResult($resual[0]);
            return $users;
        }
    }
    
    public function getRequestionListByBase($bd, $id_base){
        $query = "SELECT * FROM `change_requestion` WHERE `id_base`='".$id_base."'";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $requestions = $this->getRequestionByResult($result[$i]);
            $array[$i] = $requestions;
        }
        return $array;
    }
    
    public function getRequestionListByUserBase($bd, $id_user, $id_base){
        $query = "SELECT * FROM `change_requestion` WHERE `id_base`='".$id_base."' AND `id_user`='".$id_user."'";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $requestions = $this->getRequestionByResult($result[$i]);
            $array[$i] = $requestions;
        }
        return $array;
    }
    
     public function getRequestionListByUser($bd, $id_user){
        $query = "SELECT * FROM `change_requestion` WHERE  `id_user`='".$id_user."'";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $requestions = $this->getRequestionByResult($result[$i]);
            $array[$i] = $requestions;
        }
        return $array;
    }
            
    
    function insert($bd, $id_admins, $requestion){
        $filds = "";
        $filds .= "Логин: ".$requestion->login;
        $filds .= " C: ".$requestion->date_from;
        $filds .= " По: ".$requestion->date_to;
        $filds .= " Выгрузка: ".$requestion->date_upload;
        $filds .= " Заявка: ".$requestion->number;
        $filds .= " ".$requestion->state.",".$requestion->request;
        $filds = str_replace("'", "", $filds);
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO `change_requestion`(`id_user`, `id_base`, `id_admins`, `filds`, `notice`, `date_change`) VALUES ('".$requestion->id_user."','".$requestion->id_base."','".$id_admins."','".$filds."','".$requestion->notice."','".$date."')";
        $bd->queryClean($query);        
    }
    
}
