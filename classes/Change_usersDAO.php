<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Change_usersDAO
 *
 * @author 301_Kralin_A_V
 */
class Change_usersDAO {
    private function getChangeUsersByResult($result){
        $user = new Change_users();
        $user->id = $result['id'];
        $user->fio = $result['fio'];
        $user->ip = $result['ip'];
        $user->telefon = $result['telefon'];
        $user->id_imns = $result['id_imns'];
        $user->id_jobs = $result['id_jobs'];
        $user->id_user = $result['id_user'];
        $user->change_date = $result['change_date'];
        return $user;
    }
    
    function getUserById($bd, $id){
        $query = "SELECT * FROM `change_users` WHERE `id`='".$id."'";
        $result = $bd->query($query);
        if ($result != null){
            $user = $this->getChangeUsersByResult($result[0]);
            return $user;
        }
    }
    
    function getUsersByIdUsers($bd, $id_user){
        $query = "SELECT * FROM `change_users` WHERE `id_user`='".$id_user."'";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $users = $this->getChangeUsersByResult($result[$i]);
            $array[$i] = $users;
        }
        return $array;
    }
    
}
