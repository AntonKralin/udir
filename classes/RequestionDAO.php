<?php
require_once 'Requestion.php';
require_once 'BD.php';
require_once './otherPHP/function.php';
require_once 'Change_requestionDAO.php';

/**
 * Description of RequestionDAO
 *
 * @author 301_Kralin_A_V
 */
class RequestionDAO {
    private function getRequestionByResult($result){
        $requestion = new Requestion();
        $requestion->id = $result['id'];
        $requestion->id_user = $result['id_user'];
        $requestion->id_base = $result['id_base'];
        $requestion->id_imns = $result['id_imns'];
        $requestion->login = $result['login'];
        $requestion->date_from = $result['date_from'];
        $requestion->date_to = $result['date_to'];
        $requestion->date_upload = $result['date_upload'];
        $requestion->state = $result['state'];
        $requestion->request = $result['request'];
        $requestion->number = $result['number'];
        $requestion->notice = $result['notice'];
        return $requestion;
    }
    
    function getRequestionById($bd, $id){
        $query = "SELECT * FROM `requestion` WHERE `id`='".$id."'";
        $data = $bd->query($query);
        if($data != null){
            $result = $this->getRequestionByResult($data[0]);
            return $result;
        }
    }
    
    function getRequestionByBaseRegion ($bd, $id_base, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r JOIN `imns` i ON r.`id_imns`=i.`id` JOIN `region` q ON q.`id`=i.`id_region` WHERE q.`id`='".$id_region."' AND r.`id_base`='".$id_base."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getActivRequestionByBaseRegion ($bd, $id_base, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r JOIN `imns` i ON r.`id_imns`=i.`id` JOIN `region` q ON q.`id`=i.`id_region` WHERE q.`id`='".$id_region."' AND r.`id_base`='".$id_base."' AND (r.`state`!='прекращен' AND r.`state`!='отказано')";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByBaseStateRegion($bd, $id_base, $state, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r JOIN `imns` i ON r.`id_imns`=i.`id` JOIN `region` q ON q.`id`=i.`id_region` WHERE q.`id`='".$id_region."' AND r.`id_base`='".$id_base."' AND r.`state`='".$state."' ORDER BY r.`id_imns`";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;        
    }

    function getRequestionByBaseStateRequest($bd, $id_base, $state, $request){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r WHERE r.`request`='".$request."' AND r.`id_base`='".$id_base."' AND r.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;        
    }
    
    function getRequestionByBaseStateRequestImns($bd, $id_base, $state, $request, $imns){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r WHERE r.`request`='".$request."' AND r.`id_base`='".$id_base."' AND r.`state`='".$state."' AND r.`id_imns`='".$imns."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;        
    }
    
    function getRequestionByUser($bd, $id_user){
        $query = "SELECT * FROM `requestion` WHERE `id_user`='".$id_user."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByUserBase($bd, $id_user, $id_base){
        $query = "SELECT * FROM `requestion` WHERE `id_user`='".$id_user."' AND `id_base`='".$id_base."'";
        $data = $bd->query($query);
        $result = null;
        for($i=0; $i<count($data); $i++){
            $result = $this->getRequestionByResult($data[$i]);            
        }
        return $result;
    }
    
    function getRequestionByStateImns($bd, $state, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_imns`='".$id_imns."' AND `state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByStateRegion($bd, $state, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r JOIN `imns` i ON r.`id_imns`=i.`id` JOIN `region` q ON q.`id`=i.`id_region` WHERE q.`id`='".$id_region."' AND r.`state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByImns($bd, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_imns`='".$id_imns."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByRegion($bd, $id_region){
        $query = "SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` r JOIN `imns` i ON r.`id_imns`=i.`id` JOIN `region` q ON q.`id`=i.`id_region` WHERE q.`id`='".$id_region."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByBaseImns ($bd, $id_base, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_base`='".$id_base."' AND `id_imns`='".$id_imns."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getActivRequestionByBaseImns ($bd, $id_base, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_base`='".$id_base."' AND `id_imns`='".$id_imns."' AND (`state`!='прекращен' AND `state`!='отказано')";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionByBaseStateImns($bd, $id_base, $state, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_base`='".$id_base."' AND `id_imns`='".$id_imns."' AND `state`='".$state."'";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionTerminateByImns($bd, $id_imns){
        $query = "SELECT * FROM `requestion` WHERE `id_imns`='".$id_imns."' AND (`state`='прекращен' or `state`='отказано')";
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }
    
    function getRequestionOlder5YearsByRegion($bd, $id_region){
        $query = 'SELECT r.`id`, r.`id_user`, r.`id_base`, r.`id_imns`, r.`login`, r.`date_from`, r.`date_to`, r.`date_upload`, r.`state`, r.`request`, r.`number`, r.`notice` FROM `requestion` AS r JOIN `imns` AS i ON r.`id_imns`=i.`id` WHERE r.`date_to` < DATE_ADD(CURRENT_DATE(), INTERVAL - 5 YEAR) and r.`state` = "прекращен" and i.`id_region`='.$id_region;
        $data = $bd->query($query);
        $array = [];
        for($i=0; $i<count($data); $i++){
            $base = $this->getRequestionByResult($data[$i]);
            $array[$i]=$base;            
        }
        return $array;
    }

    function getActiveCount($bd, $id_base, $id_imns){
        $query = 'SELECT COUNT(*) FROM `requestion` WHERE `id_base`="'.$id_base.'" AND `id_imns`="'.$id_imns.'" AND !((`request`="предоставление" and `state`="выгружен на область") OR (`request`="предоставление" and `state`="отправлен в МНС") or (`state`="прекращен") or (`state`="отказано"))';
        $data = $bd->query($query);
        $rez = 0;
        if ($data){
            $rez = $data[0]['COUNT(*)'];
        }
        return $rez;
    }
    
    function  getCountBaseUser($bd, $id_base, $id_user){
        $query = "SELECT COUNT(*) FROM `requestion` WHERE `id_base`='".$id_base."' AND `id_user`='".$id_user."'";
        $data = $bd->query($query);
        $rez = 0;
        if ($data){
            $rez = $data[0]['COUNT(*)'];
        }
        return $rez;
    }
    
    function getCountBaseLogin($bd, $id_base, $login){
        $query = "SELECT COUNT(*) FROM `requestion` WHERE `id_base`='".$id_base."' AND `login` LIKE'".$login."%'";
        $data = $bd->query($query);
        $rez = 0;
        if ($data){
            $rez = $data[0]['COUNT(*)'];
        }
        return $rez;
    }


    function insertFild($bd, $id_user, $id_base, $id_imns, $login, $date_from, $date_to, $date_upload, $state, $request, $number, $notice){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $date_upload = checkDateSQL($date_upload);
        $query = "INSERT INTO `requestion`(`id_user`, `id_base`, `id_imns`, `login`, `date_from`, `date_to`, `date_upload`, `state`, `request`, `number`, `notice`) VALUES ('".$id_user."','".$id_base."','".$id_imns."','".$login."',".$date_from.",".$date_to.",".$date_upload.",'".$state."','".$request."','".$number."','".$notice."');";
        $bd->queryClean($query);
    }

    function insertFild2($bd, $id_user, $id_base, $id_imns, $login,  $date_upload, $state, $request, $number, $notice, $id_admins){
        $date_upload = checkDateSQL($date_upload);
        $query = "INSERT INTO `requestion`(`id_user`, `id_base`, `id_imns`, `login`, `date_upload`, `state`, `request`, `number`, `notice`) VALUES ('".$id_user."','".$id_base."','".$id_imns."','".$login."',".$date_upload.",'".$state."','".$request."','".$number."','".$notice."');";
        //echo $query;
        $bd->queryClean($query);
        $requestion = new Requestion();
        $requestion->id_base = $id_base;
        $requestion->id_user = $id_user;
        $requestion->id_imns = $id_imns;
        $requestion->login = $login;
        $requestion->date_from = null;
        $requestion->date_to = null;
        $requestion->date_upload = $date_upload;
        $requestion->number = $number;
        $requestion->state = $state;
        $requestion->request = $request;
        $requestion->notice = $notice;
        $changeRequestionDAO = new Change_requestionDAO();
        $changeRequestionDAO->insert($bd, $id_admins, $requestion);
    }
    
    function updateFild($bd, $id, $id_user, $id_base, $id_imns, $login, $date_from, $date_to, $date_upload, $state, $request, $number, $notice){
        $query = "UPDATE `requestion` SET `id_user`='".$id_user."', `id_base`='".$id_base."', `id_imns`='".$id_imns."', `login`='".$login."', `date_from`='".$date_from."', `date_to`='".$date_to."', `date_upload`='".$date_upload."', `state`='".$state."', `request`='".$request."', `number`='".$number."', `notice`='".$notice."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function updateFild2($bd, $id, $login, $date_from, $date_to, $date_upload, $state, $request, $number, $notice, $id_admins){
        $date_from = checkDateSQL($date_from);
        $date_to = checkDateSQL($date_to);
        $date_upload = checkDateSQL($date_upload);
        if (!$state){
            $state = '';
        }else{
            $state = ", `state`='".$state."'";
        }
        if (!$request){
            $request = '';
        }else{
            $request = ", `request`='".$request."'";
        }
        $query = "UPDATE `requestion` SET `login`='".$login."', `date_from`=".$date_from.", `date_to`=".$date_to.", `date_upload`=".$date_upload." ".$state.$request.", `number`='".$number."', `notice`='".$notice."' WHERE `id`='".$id."'";
        $bd->queryClean($query);
        $requestion = $this->getRequestionById($bd, $id);
        $changeRequestionDAO = new Change_requestionDAO();
        $changeRequestionDAO->insert($bd, $id_admins, $requestion);
    }
    
    function updateState($bd, $id, $state){
        $query = "UPDATE `requestion` SET `state`='".$state."' WHERE `id`='".$id."'";
        if ($state == "отправлен в МНС"){
            $tdata = date("Y-m-d");
            $query = "UPDATE `requestion` SET `state`='".$state."', `date_upload`='".$tdata."' WHERE `id`='".$id."'";
        }
        $bd->queryClean($query);
    }
    
    function deleteFild($bd, $id){
        $query = "DELETE FROM `requestion` WHERE `id`='".$id."'";
        $bd->queryClean($query);
    }
    
    function createNewLogin($bd, $id_imns, $id_user, $id_base){
        $baseDAO = new BaseDAO();
        $base = $baseDAO->getBaseById($bd, $id_base);
        $new_login = $base->new_login;
        
        if ($new_login == ""){
            return "";
        }
        $userDao = new UsersDao();
        $user = $userDao->getUsersById($bd, $id_user);
        $imnsDAO = new ImnsDAO();
        $imns = $imnsDAO->getImnsById($bd, $id_imns);
        
        $posIMNS = strpos($new_login, "%IMNS");
        if($posIMNS !== false){
            $new_login = str_replace("%IMNS", $imns->number, $new_login);
        }
        
        $posIP = strpos($new_login, "%IP");
        if ($posIP !== false){
            $ip = ipStandart($user->ip);
            $new_login = str_replace("%IP", $ip, $new_login);
        }
        
        $posFIO = strpos($new_login, "%FIO");
        if ($posFIO !== false){
            $fio = getFullInitials($user->fio);
            $new_login = str_replace("%FIO", $fio, $new_login);
        }
        
        $posF = strpos($new_login, "%F");
        if ($posF !== false){
            $f = getFamily($user->fio);
            $new_login = str_replace("%F", $f, $new_login);
        }
        
        $posN = strpos($new_login, "%N");
        if ($posN !== false){
            $n = getName($user->fio);
            $new_login = str_replace("%N", $n, $new_login);
        }
        
        $posP = strpos($new_login, "%P");
        if ($posP !== false){
            $p = getPatronymic($user->fio);
            $new_login = str_replace("%P", $p, $new_login);
        }
        
        $posI = strpos($new_login, "%I");
        if( $posI !== false){
            $i = getInitials($user->fio);
            $new_login = str_replace("%I", $i, $new_login);
        }
        
        $posC = strpos($new_login, "%C(");
        if ($posC !== false){
            $posC2 = strpos($new_login, ")", $posC);
            $sim = substr($new_login, $posC+3, $posC2-$posC-3);
            $count = $this->getCountBaseLogin($bd, $id_base, substr($new_login,0, $posC) );
            if ($count != "0"){
                $new_login = substr($new_login, 0, $posC).$sim.$count.mb_substr($new_login, $posC2+1);
            }else{
                $new_login = substr($new_login, 0, $posC).mb_substr($new_login, $posC2+1);
            }
        }
            
        return $new_login;
    }
}
