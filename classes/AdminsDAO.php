<?php
require_once 'Admins.php';
require_once 'BD.php';

/**
 * Description of AdminsDAO
 *
 * @author 301_Kralin_A_V
 */
class AdminsDAO {
        
    function setField($id, $login, $password, $id_imns, $id_access, $smtp){
        $admins = new Admins();
        $admins->id = $id;
        $admins->login = $login;
        $admins->password = $password;
        $admins->id_imns = $id_imns;
        $admins->id_access = $id_access;
        $admins->smtp = $smtp;
        return $admins;
    }
    
    function getAdminByLogin($bd, $login){
        $query = 'select * from `admins` where `login`="'.$login.'"';
        $data = $bd->query($query);
        if ($data != null){
            $elem = $data[0];
            $admins = new Admins();
            $admins->id  = $elem['id'];
            $admins->login = $elem['login'];
            $admins->password = $elem['password'];
            $admins->id_imns = $elem['id_imns'];
            $admins->id_access = $elem['id_access'];
            $admins->smtp = $elem['smtp'];
            return $admins;
        }
    }
    
    function getAdminById($bd, $id){
        $query = 'select * from `admins` where `id`="'.$id.'"';
        $data = $bd->query($query);
        if ($data != null){
            $elem = $data[0];
            $admins = new Admins();
            $admins->id  = $elem['id'];
            $admins->login = $elem['login'];
            $admins->password = $elem['password'];
            $admins->id_imns = $elem['id_imns'];
            $admins->id_access = $elem['id_access'];
            $admins->smtp = $elem['smtp'];
            return $admins;
        }
    }
    
    function updateFild($bd, $id, $login, $password, $id_imns, $id_access, $smtp){
        $query = "UPDATE `admins` SET `login`='".$login."', `password`='".$password."', `id_imns`='".$id_imns."', `id_access`='".$id_access."',`smtp`='".$smtp."' WHERE `id`=".$id;
        $bd->queryClean($query);
        #return $result;
    }
    
    function  insertFild($bd, $login, $password, $id_imns, $id_access, $smtp){
        $query = "INSERT INTO `admins`(`login`, `password`, `id_imns`, `id_access`, `smtp`) VALUES('".$login."','".$password."','".$id_imns."','". $id_access."','".$smtp."');";
        $bd->queryClean($query);
        #return $result;        
    }


    function getAdminList($bd, $id_imns, $admins){
        $resualt = [];
        if ($admins->id_access == "4"){
            $imnsDAO = new ImnsDAO();
            $imns = $imnsDAO->getImnsById($bd, $id_imns);
            $query = 'SELECT * FROM `admins` where `id_imns` = "'.$admins->id_imns.'"';
            $data = $bd->query($query);
            for($i=0; $i<count($data); $i++){
                $adminsDAO = new AdminsDAO();
                $admins = $adminsDAO->setField($data[$i]['id'], $data[$i]['login'], $data[$i]['password'], $data[$i]['id_imns'], $data[$i]['id_access'], $data[$i]['smtp']);
                $resualt[$i] = $admins;
            }
            return $resualt;
        }
        if ($admins->id_access == "3"){
            $imnsDAO = new ImnsDAO();
            $imns = $imnsDAO->getImnsById($bd, $id_imns);
            $query = 'SELECT a.`id`, a.`login`, a.`password`, a.`id_imns`, a.`id_access`, a.`smtp` FROM `admins` a JOIN `imns` i on i.`id`=a.`id_imns` where i.`id_region` = "'.$imns->id_region.'"';
            $data = $bd->query($query);
            for($i=0; $i<count($data); $i++){
                $adminsDAO = new AdminsDAO();
                $admins = $adminsDAO->setField($data[$i]['id'], $data[$i]['login'], $data[$i]['password'], $data[$i]['id_imns'], $data[$i]['id_access'], $data[$i]['smtp']);
                $resualt[$i] = $admins;
            }
            return $resualt;
        }
    }
}
