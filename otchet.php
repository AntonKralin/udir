<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
if (isset($_SESSION['base'])){
    $base = unserialize($_SESSION['base']);
}
$select_imns = 0;
if ( isset( $_SESSION['select_imns'] ) ){
    $select_imns = $_SESSION['select_imns'];
}
$bd = new BD();
}else{
    exit;
}

?>
<html>

	<head>
            <title>«Учет доступа к информационным ресурсам»</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Учет доступа к информационным ресурсам" />
            <meta name="keywords" content="Учет доступа к информационным ресурсам" />
            <meta name="viewport" content="width=device-width">
            <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />        
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?5"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
            
            <style>
                table.CSSTable {
                    border-width: 1px;
                    border-spacing: 0px;
                    border-style: solid;
                    border-color: black;
                    border-collapse: collapse;
                    background-color: white;
                }
                table.CSSTable th {
                    border-width: 1px;
                    padding: 0px;
                    border-style: solid;
                    border-color: black;
                    background-color: white;
                }
                table.CSSTable td {
                    border-width: 1px;
                    padding: 0px;
                    border-style: solid;
                    border-color: black;
                    background-color: white;
                }                
            </style>
            
	</head>

	<body>
            <?php 
                $type = array("прекращение", "предоставление", "продление", "разблокировка", "изменить реквизиты");
                $avtoLineDAO = new AvtoLineDAO();
                $jobsDAO = new JobsDAO();
                $unitDAO = new UnitDAO();
                $requestionDAO = new RequestionDAO();
                $userDAO = new UsersDao();
                $imnsDAO = new ImnsDAO();
                
                $maxColumn = 30;
                echo '<table class="CSSTable" width="100%" cols="'.$maxColumn.'">';
                
                $type = $avtoLineDAO->getAvtoLineTypeByBase($bd, $base->id);
                for ($i=0; $i<count($type); $i++){
                    if ($select_imns == "0"){
                        $requestionList = $requestionDAO->getRequestionByBaseStateRequest($bd, $base->id, "выгружен на область", $type[$i]);
                    }else{
                        $requestionList = $requestionDAO->getRequestionByBaseStateRequestImns($bd, $base->id, "выгружен на область", $type[$i], $imns->id);
                    }
                    if ($requestionList == null) {
                        continue;
                    }
                    $avtoLineList = $avtoLineDAO->getAvtoLineByBaseType($bd, $base->id, $type[$i]);
                    if ($avtoLineList == null){
                        continue;
                    }
                    
                    
                    $column = count($avtoLineList);
                    
                        
                        $count = 0;
                        foreach ($avtoLineList as $avtoline){
                            
                            if ($count == 0){
                                echo "<tr><td align='center' colspan='".$maxColumn."'>".$avtoline->yourname."</td></tr>";
                                echo "<tr>";
                            }
                            $count++;
                            $colspan = "";
                            if ($count == $column){
                                $zn = $maxColumn - $column+1;
                                $colspan = " colspan='".$zn."'";
                            }
                            echo "<td ".$colspan.">".$avtoline->name."</td>";
                        }
                        echo "</tr>";
                        
                        foreach ($requestionList as $line){
                            echo "<tr>";
                            $count = 0;
                            
                            foreach ($avtoLineList as $avtoline){
                                $str = $avtoline->date;
                                $lineImns = $imnsDAO->getImnsById($bd, $line->id_imns);
                                $lineUser = $userDAO->getUsersById($bd, $line->id_user);
                                
                                $posNNU = strpos($str, "%NNU");
                                if ($posNNU !== false){
                                    $str = str_replace("%NNU", $lineImns->number, $str);
                                }
                                
                                $posNNA = strpos($str, "%NNA");
                                if ($posNNA !== false){
                                    $str = str_replace("%NNA", $lineImns->name, $str);
                                }
                                
                                $posNU = strpos($str, "%NU");
                                if ($posNU !== false){
                                    $str = str_replace("%NU", $lineImns->unp, $str);
                                }
                                
                                $posNA = strpos($str, "%NA");
                                if ($posNA !== false){
                                    $str = str_replace("%NA", $lineImns->address, $str);
                                }
                                
                                $posNP = strpos($str, "%NP");
                                if ($posNP !== false){
                                    $str = str_replace("%NP", $lineImns->post, $str);
                                }
                                
                                $posNM = strpos($str, "%NM");
                                if ($posNM !== false){
                                    $str = str_replace("%NM", $lineImns->mail, $str);
                                }
                                
                                $posNSN = strpos($str, "%NSN");
                                if ($posNSN !== false){
                                    $str = str_replace("%NSN", $lineImns->shot_name, $str);
                                }
                                
                                $posNBI = strpos($str, "%NBI");
                                if ($posNBI !== false){
                                    $str = str_replace("%NBI", $lineImns->bik, $str);
                                }
                                
                                $posNSC = strpos($str, "%NSC");
                                if ($posNSC !== false){
                                    $str = str_replace("%NSC", $lineImns->score, $str);
                                }
                                
                                $posNBA = strpos($str, "%NBA");
                                if ($posNBA !== false){
                                    $str = str_replace("%NBA", $lineImns->bank_address, $str);
                                }
                                
                                $posNB = strpos($str, "%NB");
                                if ($posNB !== false){
                                    $str = str_replace("%NB", $lineImns->bank, $str);
                                }
                                
                                $posJN = strpos($str, "%JN");
                                if ($posJN !== false){
                                    $lineJob = $jobsDAO->getJobsById($bd, $lineUser->id_jobs);
                                    $str = str_replace("%JN", $lineJob->name, $str);
                                }
                                
                                $posUFS = strpos($str, "%UFS");
                                if ($posUFS !== false){
                                    $str = str_replace("%UFS", getRusFalily($lineUser->fio), $str);
                                }
                                
                                $posUFN = strpos($str, "%UFN");
                                if ($posUFN !== false){
                                    $str = str_replace("%UFN", getRusName($lineUser->fio), $str);
                                }
                                
                                $posUFP = strpos($str, "%UFP");
                                if ($posUFP !== false){
                                    $str = str_replace("%UFP", getRusPatronymic($lineUser->fio), $str);
                                }
                                
                                $posRU = strpos($str, "%RU");
                                if ($posRU !== false){
                                    $str = str_replace("%RU", $line->number, $str);
                                }
                                
                                $posUF = strpos($str, "%UF");
                                if ($posUF !== false){
                                    $str = str_replace("%UF", $lineUser->fio, $str);
                                }
                                
                                $posUI = strpos($str, "%UI");
                                if ($posUI !== false){
                                    $str = str_replace("%UI", $lineUser->ip, $str);
                                }
                                
                                $posUT = strpos($str, "%UT");
                                if ($posUT !== false){
                                    $str = str_replace("%UT", $lineUser->telefon, $str);
                                }
                                
                                $posRL = strpos($str, "%RL");
                                if ($posRL !== false){
                                    $str = str_replace("%RL", $line->login, $str);
                                }
                                
                                $posUN = strpos($str, "%UN");
                                if ($posUN !== false){
                                    $lineUnit = $unitDAO->getUnitById($bd, $lineUser->id_unit);
                                    $str = str_replace("%UN", $lineUnit->name, $str);
                                }
                                
                                $posRN = strpos($str, "%RN");
                                if ($posRN !== false){
                                    $str = str_replace("%RN", $line->notice, $str);
                                }
                                
                                $count++;
                                $colspan = "";
                                if ($count == $column){
                                    $zn = $maxColumn - $column+1;
                                    $colspan = " colspan='".$zn."'";
                                }
                                echo "<td ".$colspan.">".$str."</td>";
                            }
                            echo "</tr>";
                        }
                }
                
                echo "</table>";
                
            ?>
	
	</body>
	
</html>