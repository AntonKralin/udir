<?php
require_once 'Users.php';
require_once 'BD.php';

/**
 * Description of UsersDao
 *
 * @author 301_Kralin_A_V
 */
class UsersDao {
    
    private function getUsersByResult($result){
        $users = new Users();
        $users->id = $result['id'];
        $users->fio = $result['fio'];
        $users->ip = $result['ip'];
        $users->ad_login = $result['ad_login'];
        $users->telefon = $result['telefon'];
        $users->active = $result['active'];
        $users->comp_name = $result['comp_name'];
        $users->id_jobs = $result['id_jobs'];
        $users->id_imns = $result['id_imns'];
        $users->id_unit = $result['id_unit'];
        return $users;
    }
    
    function getUsersById($bd, $id){
        $query = "SELECT * FROM `users` WHERE `id`='".$id."'";
        $resual = $bd->query($query);
        if ($resual != null){
            $users = $this->getUsersByResult($resual[0]);
            return $users;
        }
    }
    
    function  getUsersList($bd, $id_imns){
        $query = "SELECT * FROM `users` WHERE `id_imns`='".$id_imns."'";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $users = $this->getUsersByResult($result[$i]);
            $array[$i] = $users;
        }
        return $array;
    }
    
    function  getUsersActiveList($bd, $id_imns, $active){
        $query = "SELECT * FROM `users` WHERE `active`='".$active."' AND `id_imns`='".$id_imns."' ORDER BY `fio`";
        $result = $bd->query($query);
        $array = [];
        for ($i=0; $i<count($result); $i++){
            $users = $this->getUsersByResult($result[$i]);
            $array[$i] = $users;
        }
        return $array;
    }
    
    function updateUsers($bd, $id, $fio, $ip, $telefon,$id_unit, $id_jobs){
        $query = "UPDATE `users` SET `fio`='".$fio."', `ip`='".$ip."', `telefon`='".$telefon."',`id_unit`='".$id_unit."', `id_jobs`='".$id_jobs."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function activateUsers($bd, $id, $active){
        $query = "UPDATE `users` SET `active`='".$active."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function insertUsers($bd, $fio, $ip, $telefon, $id_unit, $id_jobs, $imns){
        $query = "INSERT INTO `users`(`fio`, `ip`, `telefon`, `id_unit`, `id_jobs`, `id_imns`, `active`) VALUES ('".$fio."','".$ip."','".$telefon."','".$id_unit."','".$id_jobs."','".$imns."','0')";
        $bd->queryClean($query);        
    }
    
    function deleteUser($bd, $id){
        $query = "DELETE FROM `users` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
}
