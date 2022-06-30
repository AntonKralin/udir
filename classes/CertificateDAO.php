<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CertificateDAO
 *
 * @author 301_Kralin_A_V
 */
class CertificateDAO {
    
    private function getCertificateByResualt($resualt){
        $certificate = new Certificate();
        $certificate->id = $resualt['id'];
        $certificate->name = $resualt['name'];
        $certificate->number = $resualt['number'];
        $certificate->date_from = $resualt['date_from'];
        $certificate->date_to = $resualt['date_to'];
        $certificate->state = $resualt['state'];
        $certificate->reason = $resualt['reason'];
        $certificate->id_user = $resualt['id_user'];
        return $certificate;
    }
    
    public function getCertificateById($bd, $id){
        $query = "SELECT * FROM `certificate` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $result = $this->getCertificateByResualt($data[0]);
            return $result;
        }
    }
    
    public function getCertificateByUserState($bd, $id_user, $state){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c WHERE c.`id_user`='".$id_user."' AND `state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByImns($bd, $id_imns){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` WHERE u.`id_imns`='".$id_imns."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByImnsState($bd, $id_imns, $state){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` WHERE u.`id_imns`='".$id_imns."' AND `state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByImnsName($bd, $id_imns, $name){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` WHERE u.`id_imns`='".$id_imns."' AND `name`='".$name."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByImnsNameState($bd, $id_imns, $name, $state){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` WHERE u.`id_imns`='".$id_imns."' AND `name`='".$name."' AND c.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByRegion($bd, $id_region){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` JOIN `imns` i ON u.`id_imns` = i.`id` WHERE i.`id_region`='".$id_region."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByRegionState($bd, $id_region, $state){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` JOIN `imns` i ON u.`id_imns` = i.`id` WHERE i.`id_region`='".$id_region."' AND c.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByRegionName($bd, $id_region, $name){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` JOIN `imns` i ON u.`id_imns` = i.`id` WHERE i.`id_region`='".$id_region."' AND c.`name`='".$name."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }
    
    public function getCertificateByRegionNameState($bd, $id_region, $name, $state){
        $query = "SELECT c.`id`, c.`name`, c.`number`, c.`date_from`, c.`date_to`, c.`state`, c.`reason`, c.`id_user` FROM `certificate` c JOIN `users` u ON c.`id_user` = u.`id` JOIN `imns` i ON u.`id_imns` = i.`id` WHERE i.`id_region`='".$id_region."' AND c.`name`='".$name."' AND c.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $certificate = $this->getCertificateByResualt($data[$i]);
            $array[$i]=$certificate;            
        }
        return $array;
    }    
    
    public function insert($bd, $name, $date_from, $date_to, $reason, $state, $id_user, $number){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $query = "INSERT INTO `certificate`(`name`, `date_from`, `date_to`, `reason`, `state`, `id_user`, `number`) VALUES ('".$name."',".$date_from.",".$date_to.",'".$reason."','".$state."','".$id_user."', '".$number."');";
        $bd->queryClean($query);
    }
    
    public function update($bd, $id, $name, $date_from, $date_to, $reason, $state, $number){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $query = "UPDATE `certificate` SET name='".$name."', `date_from`=".$date_from.", `date_to`=".$date_to.", `reason`='".$reason."', `state`='".$state."', `number`='".$number."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    public function delete($bd, $id) {
        $query = "DELETE FROM `certificate` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
}
