<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';
require_once 'otherPHP/PHPExcel.php';

const _EXPIRE_DAY = "+2 month";

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$imnsList = unserialize($_SESSION['imnsList']);
$region = unserialize($_SESSION['region']);
$_SESSION["HTTP_REFERER"] = 'main.php';
$base = null;
if (isset($_SESSION['base'])){
    $base = unserialize($_SESSION['base']);
}

$select_imns = $admins->id_imns;
if (isset($_SESSION['select_imns'])){
    $select_imns = $_SESSION['select_imns'];
}

$bd = new BD();
}else{
    exit;
}

if($admins == null){exit;}

$localBaseDao = new Local_baseDAO();
$inputs = filter_input_array(INPUT_POST);

if(isset($inputs["base_click"])){
    $baseDao = new BaseDAO();
    $base = $baseDao->getBaseById($bd, $inputs['base_click']);
    if ($base){
        $_SESSION["base"] = serialize($base);
    }
}

if(isset($inputs["imns_list"])){
    $selectId = $inputs["imns_list"];
    $_SESSION['select_imns']=$selectId;
    $select_imns = $selectId;
    $imnsDAO = new ImnsDAO();
    if($selectId == "0"){
        $imns = $imnsDAO->getImnsById($bd, $admins->id_imns);
    }else{
        $imns = $imnsDAO->getImnsById($bd, $selectId);
    }
    $_SESSION["imns"] = serialize($imns);
}

if(isset($inputs["adminAddForm"])){
    $selectId = $inputs['adminsListId'];
    $postAdminLogin = $inputs['loginId'];
    $postAdminPassword1 = $inputs['password1'];
    $postAdminSmtp = $inputs['smtp'];
    $postAdminAccess = $inputs['adminAccessId'];
    $postAdminImns = $inputs['adminImnsListId'];

    if ($selectId == ""){
        $bufAdmin = new AdminsDAO();
        if ($admins->id_access <=4){
            $result = $bufAdmin->insertFild($bd, $postAdminLogin, $postAdminPassword1, $postAdminImns, $postAdminAccess, $postAdminSmtp);
        }
        #echo $result;
    }else{
        $bufAdmin = new AdminsDAO();
        $result = $bufAdmin->updateFild($bd, $selectId, $postAdminLogin, $postAdminPassword1, $postAdminImns, $postAdminAccess, $postAdminSmtp);
        #echo $result;
    }
}

if(isset($inputs["imnsAddForm"])){
    #echo "imnsAddForm";
    $selectId = $inputs["imnsFormId"];
    $imns->name = $inputs["imnsFormName"];
    $imns->number = $inputs["imnsFormNumber"];
    $imns->unp = $inputs["imnsFormUnp"];
    $imns->post = $inputs["imnsFormPost"];
    $imns->address = $inputs["imnsFormAddress"];
    $imns->mail = $inputs["imnsFormMail"];
    $imns->shot_name = $inputs["imnsFormShotName"];
    $imns->bik = $inputs["imnsFormBik"];
    $imns->score = $inputs["imnsFormScore"];
    $imns->bank = $inputs["imnsFormBank"];
    $imns->bank_address = $inputs["imnsFormBankAddress"];
    $imnsDAO = new ImnsDAO();
    $imnsDAO->updateImns($bd, $imns);
}

if(isset($inputs["saveJobs"])){
    $selectId = $inputs["jobsId"];
    $nameJobs = $inputs["nameJobs"];
    $jobsDAO = new JobsDAO();
    if($selectId == ""){
        $jobsDAO->insertFild($bd, $nameJobs, $region->id);
    }else{
        $jobsDAO->updateFild($bd, $selectId, $nameJobs);
    }
}

if(isset($inputs["deleteJobs"])){
    $selectId = $inputs["jobsId"];
    if($selectId != ""){
        $jobsDAO = new JobsDAO();
        $jobsDAO->deleteFild($bd, $selectId);
    }
}

if(isset($inputs["saveUnit"])){
    $selectId = $inputs["unitId"];
    $nameUnit = $inputs["nameUnit"];
    $unitDAO = new UnitDAO();
    if ($selectId == ""){
        $unitDAO->insertFild($bd, $nameUnit, $region->id);
    }else{
        $unitDAO->updateFild($bd, $selectId, $nameUnit);
    }
}

if(isset($inputs["deleteUnit"])){
    $selectId = $inputs["unitId"];
    if($selectId != ""){
        $unitDAO = new UnitDAO();
        $unitDAO->deleteFild($bd, $selectId);
    }
}

if(isset($inputs["userSubmit"])){
    $selectId=$inputs["userSelectId"];
    $fio = $inputs["userFio"];
    $ip = $inputs["userIP"];
    $telefon = $inputs["userTelefon"];
    $jobs = $inputs["userJobs"];
    $unit = $inputs["userUnit"];
    $usersDAO = new UsersDao();
    if($selectId == ""){
        $usersDAO->insertUsers($bd, $fio, $ip, $telefon,$unit, $jobs, $imns->id);
    }else{
        $usersDAO->updateUsers($bd, $selectId, $fio, $ip, $telefon,$unit, $jobs);
    }
}

if(isset($inputs["disableSubmit"])){
    $selectId=$inputs["userSelectId"];
    $usersDAO = new UsersDao();
    $active = "1";
    $usersDAO->activateUsers($bd, $selectId, $active);
}

if(isset($inputs["activeSubmit"])){
    $selectId=$inputs["userDisable"];
    $usersDAO = new UsersDao();
    $active = "0";
    $usersDAO->activateUsers($bd, $selectId, $active);
}

if(isset($inputs["deleteUser"])){
    $selectId=$inputs["userDisable"];
    $usersDAO = new UsersDao();
    $usersDAO->deleteUser($bd, $selectId);
}


if(isset($inputs["saveBase"])){
    $selectId = $inputs['baseId'];
    $name = $inputs['baseName'];
    $shot = $inputs['baseShotName'];
    $newLogin = $inputs['baseNewLogin'];
    $baseNotice = $inputs['baseNotice'];
    $baseArchive = 0;
    if(isset($inputs["baseArchive"])){
        $baseArchive = 1;
    }
    $baseDAO = new BaseDAO();
    if($selectId != ""){
        $baseDAO->updateBase($bd, $selectId, $name, $shot, $newLogin, $baseNotice, $baseArchive);
    }else{
        $baseDAO->insertBase($bd, $name, $shot, $region->id, $newLogin, $baseNotice);
    }
}

if(isset($inputs['deleteBase'])){
    $selectId = $inputs['baseId'];
    $baseDAO = new BaseDAO();
    $baseDAO->deleteBase($bd, $selectId);
}

if(isset($inputs['saveLocalBase'])){
    $selectId = $inputs['localBaseId'];
    $baseName = $inputs['localBaseName'];
    $baseNotice = $inputs['localBaseNotice'];
    $baseArchive = 0;
    if(isset($inputs["localBaseArchive"])){
        $baseArchive = 1;
    }
    if($selectId != ""){
        $localBaseDao->updateBase($bd, $selectId, $baseName, $baseNotice, $baseArchive);
    }else{
        $localBaseDao->insertBase($bd, $baseName, $region->id, $baseNotice);
    }
}

if(isset($inputs['deleteLocalBase'])){
    $selectId = $inputs['localBaseId'];
    $localBaseDao->deleteBase($bd, $selectId);
}

$usersDAO = new UsersDao();
$jobsDAO = new JobsDAO();
$jobsList = $jobsDAO->getJobsList($bd, $region->id);
$unitDAO = new UnitDAO();
$unitList = $unitDAO->getUnitList($bd, $region->id);
$baseDAO = new BaseDAO();
$baseList = $baseDAO->getBaseList($bd, $region->id);

if (isset($inputs['exportcsv2'])){
    if($base){
        $requestionDAO = new RequestionDAO();
        $arrayRequestion = null;
        if ($select_imns == 0){
            $arrayRequestion = $requestionDAO->getActivRequestionByBaseRegion($bd, $base->id, $region->id);
        }else{
            $arrayRequestion = $requestionDAO->getActivRequestionByBaseImns($bd, $base->id, $imns->id);
        }
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue("A1","ФИО");
        $objPHPExcel->getActiveSheet()->setCellValue("B1","Подразделение");
        $objPHPExcel->getActiveSheet()->setCellValue("C1","Должность");       
        $objPHPExcel->getActiveSheet()->setCellValue("D1","IP");
        $objPHPExcel->getActiveSheet()->setCellValue("E1","Логин БД");
        $objPHPExcel->getActiveSheet()->setCellValue("F1","Дата предоставления");
        $objPHPExcel->getActiveSheet()->setCellValue("G1","Дата окончания");
        $objPHPExcel->getActiveSheet()->setCellValue("H1","Дата выгрузки");
        $objPHPExcel->getActiveSheet()->setCellValue("I1","Заявка");
        $objPHPExcel->getActiveSheet()->setCellValue("J1","Статус");
        $objPHPExcel->getActiveSheet()->setCellValue("K1","Примечание");
        if($select_imns == 0){
            $objPHPExcel->getActiveSheet()->setCellValue("L1","№");
        }
        
        $i=2;
        foreach ($arrayRequestion as $requestion){
            $user = $usersDAO->getUsersById($bd, $requestion->id_user);
            $fio = "";
            $telefon = "";
            $ip = "";        
            if ($user){
                $fio = $user->fio;
                $telefon = $user->telefon;
                $ip = $user->ip;
            }
            $name = "";
            $unit = "";
            if ($user){
                $job = $jobsDAO->getJobsById($bd, $user->id_jobs);
                $name = $job->name;
                $unit_obj = $unitDAO->getUnitById($bd, $user->id_unit);
                $unit = $unit_obj->name;
            }
            $state = $requestion->state;
            if ( ($state=="выгружен на область") || ($state=="отправлен в МНС") ){
                $state = $state.', '.$requestion->request;
            }
            
            $objPHPExcel->getActiveSheet()->setCellValue("A".$i,$fio);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$i,$unit);
            $objPHPExcel->getActiveSheet()->setCellValue("C".$i,$name);            
            $objPHPExcel->getActiveSheet()->setCellValue("D".$i,$ip);
            $objPHPExcel->getActiveSheet()->setCellValue("E".$i,$requestion->login);
            $objPHPExcel->getActiveSheet()->setCellValue("F".$i,convertDate($requestion->date_from));
            $objPHPExcel->getActiveSheet()->setCellValue("G".$i,convertDate($requestion->date_to));
            $objPHPExcel->getActiveSheet()->setCellValue("H".$i,convertDate($requestion->date_upload));
            $objPHPExcel->getActiveSheet()->setCellValue("I".$i,$requestion->number);
            $objPHPExcel->getActiveSheet()->setCellValue("J".$i,$state);
            $objPHPExcel->getActiveSheet()->setCellValue("K".$i,$requestion->notice);
            if ($select_imns == 0){
                $imnsDAO = new ImnsDAO();
                $imnsBuf = $imnsDAO->getImnsById($bd, $requestion->id_imns);
                $objPHPExcel->getActiveSheet()->setCellValue("E".$i,$imnsBuf->number);
            }
            $i++;
        }
        
        $filename=$base->shot_name.".csv";
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-control: max-age=0');
        echo "\xEF\xBB\xBF";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV')->setDelimiter(";");
        $objWriter->save('php://output');
        exit;
    }
}

if ($base == null){
    $base = $baseList[0];
    $_SESSION["base"] = serialize($base);
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
            <link rel="stylesheet" href="styles/main.css?14" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />            
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
            <script type="text/javascript" src="js/jquery.session.js"></script>
            <script type="text/javascript" src="js/sort.js?1"></script>
            <script type="text/javascript" src="js/functions.js?17"></script>
	</head>

	<body>
            <div id="top_head">
                <div id="top_left">
                    <?php 
                        
                            echo '<form id="imns_label" method="post" autocomplete="off" action="main.php">';
                            echo '<select id="imns_list" name="imns_list" onchange="document.getElementById(\'imns_label\').submit();">';
                            if ($admins->id_access <= 3 ){
                                echo "<option value='0'>All</option>";
                            }
                            foreach ($imnsList as $elem){
                                $val = "";
                                if ($elem->id == $select_imns){
                                    $val = "selected";
                                }
                                echo "<option value='".$elem->id."' ".$val.">".$elem->name."</option>";
                            }
                            echo '</select>';
                            echo '<input type="submit" id="imnsSubmit" name="imnsSubmit" style="display: none;">';
                            echo '</form>';
                        
                    ?>

                </div>
                
                <div id="top_center">
                 <!--    <?php echo "<H9>«Учет доступа к информационным ресурсам». ".$region->name.".</H9>" ?>  -->
                    <a href="main.php" class="ahead">«Учет доступа к информационным ресурсам». </a><H9 style="color: white;">Сторонние базы.</H9>
                </div>
                    
                
            </div>
            
            <div id="next_top_head">
                <form id="menu_base" method="post" autocomplete="off" action="main.php">
                    <select id="base_click" name="base_click"  style='font-weight:bold;' onchange="document.getElementById('menu_base').submit();">
                        <?php 
                            foreach ($baseList as $elem ){
                                $val = "";
                                //echo '<script>console.log('.$elem->archive.')</script>';
                                if ($elem->archive != 0){
                                    continue;
                                }
                                if ($elem->id == $base->id){
                                    $val = "selected";
                                }
                                echo "<option value='".$elem->id."' ".$val.">".$elem->shot_name."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" id="baseSubmit" name="baseSubmit" style="display: none;">
                </form> 
                <div id="top_head_button">
                    <button id='certButton' onclick = 'certButtonClick();'>Сертификаты</button>
                    <button id='locButton' onclick = 'locButtonClick();'>Локальные доступы</button>
                    <?php 
                        if ($admins->id_access <=3 ){
                            echo '<button id="unloaded" onclick="unloaded_click();"> Корректировки</button> ';
                        }
                        if ( $admins->id_access <= "4" ){
                            echo '<button id="userButton" onclick="userButtonClick();"> Пользователи</button> ';
                            echo '<button id="recourceButton" onclick="recourceButtonClick();"> Доступы</button> ';
                            echo '<button id="reportsButton" onclick="reportsButtonClick();"> Отчеты</button> ';                          
                        }
                    ?>
                </div>
                <div id="top_right">
                 <!--   <div id="top_right_name">
                        <H9>Сторонние базы </H9> &nbsp;
                    </div> -->
                    <?php 
                        if ($admins->id_access <= 4){
                        echo "<button class='userButtons' id='adminButton' onclick = 'adminButtonClick();'>"
                    . '  <img class="gwt-Image" style="width: 22px; height: 22px; cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAB6UlEQVR42r2XzytEURTHH9Ms/AU2kt+7mcb/oKEoGSz8GIoSf4KNRrIQOyMLKTUWlIzZ+JHIH6FYCIWeX0sxFuP6njrqyXv3nqd579RnM/PuOd97z733nGtZ/zClVDuwQRF8gmcwYoVlCLaq/lohrOAV4MJFwAOIlitIA9gGKZf/ZpS3zbp8HwdZ8ikN3goe2eEXLS2YApPgSJktD/pBF1gC7/w7+UxIZm6r4Iw2a7NOQFYFbzmdgBh4CzB4CSRNaZgPUMCGZBMmAxQwJBHQK3T2Cs7AKW8uiY1JBOwJdvIgiDjGRPjomU7QoS5wFVgUBG/S+KjlG1FnGad4GjQK9nlJTTYgWMFugZ8rsA46aMCTMH8vv5Tra8Wd0KdtcTmV2ImPWlIQ+vywuKZL7NiHgLwfAdK7n4pJpVDAjZ8UUHezBi4FA1LCbslk93zch50DKw21/qfpqNEErxbMPqNtXOiyMDigHd7pMXNT8B1J/saF+bvlZdwF18IxPRIB6QCLUZtEQC5AAXOSHVwKUAA1OzGdgM0QWrJlnYC6EJrSFlMaEo62nJZsgY4d6BNesQdggslza694YnHpXd5IS+WWL3p8aIJPu3yfAlugvlyvpqhH03FOpTis96FbuV0J83Wc5g1VZGxjv+9h3xqsrpra/XFRAAAAAElFTkSuQmCC" title="Администрирование">'
                        . "</button>";
                        }
                    ?>
					<form id="export" name="export" style="display: inline;" method="post" action="main.php">
                        <input type="text" id="exportcsv2" name="exportcsv2" style="display: none;" value="exportcsv2" />
                        <button class="userButtons" id="exportcsv" name="exportcsv" value="Экпорт CSV">
                            <img class="gwt-Image" style="width: 22px; height: 22px; cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABxklEQVR42tXXzytEURQH8DeLoSzGRpmlYWM5UposJpsp9lZ+hKXNWPgDaLKwFFZ2mqLUJBu2hCTTLJSy8jMlRWIMb8y4voczPG/eK+/HfZ5Tn824nfPVvHfnXkXxWwkhAtAPq5CFnAX7sAIDELYboA02oABFG57gBhahw06ABP8n1GwLZi2g9Q/is+5hCTqtBuiGA8jDJDRZQOsvxXfdwTLE7QR4hKTF8Ek4Ez/rlr+Odi8CjMKJqK4rSEGN7ABx2AZVF6AM69AiO0A9v4LTMMM2+YHegy6pATQ9aqGOjXMv6tnjSQCDB/MfBMAfgxCCXt5W3Q6Q4940I6jf+xsgBoMwB+cSAlDPeZ4R45kBhRMt8CJtuR1A35tmhmjBFDwbbB4yAwiemVJ46ywL+XUBE9DKvw8085QClIQ3dQRD0MjbMlVJEd7VCxzDGlxXPvQyANUr/8y//VWAqvJ9AJXPeE6odgPQQ5OBMYcy3Ms0gNlrKHsj+noN05yyYocPk24HoJ67ullpWhCGCDSzYTiUEIB6jmjmRAwvLw6P5WZH9byVA4mTi4nZZaXIPRO/CRDlE6zdq5mRAveMyr6cGslyr76PA4jf6h3YwRRCCJ4IEgAAAABJRU5ErkJggg==" title="Экспорт">
                        </button>
                    </form>
                    <a href="index.php" id="log_out" class="userButtons">
                        <img title="Выход" class="gwt-Image" style="width: 22px; height: 22px; cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAlklEQVR42u3WPQ6AIAwF4HcwExfvVUfu5OLgvbCr/4AtTxNe0sQJvkipAproFKTmi4BFa0BhLN9AEcTjCLIgnj2QBHFvwgbIBehjYAPiLcJtwBzXD2zAOaIU8GJKBzZgiyABhAkQZhMK8xYIcxAJcxKO7WvYAL/7La8O6LQmr0FkDoF3niColSsIamcPASu6d681W665AqpsmPc18sgbAAAAAElFTkSuQmCC"> 
                    </a>
                </div>
            </div>
               <?php    
			    echo '<label_info><font id="blink"> '.$base->notice.' </font></label_info>';
                ?>
            
                <div id="work">
                    <!-- таблица-->
                    <table border="1" width="100%" cellpadding="1" cols="12" class="sortable" >
                        <thead>
                                <tr>    
                                        <?php 
                                            if ($select_imns == 0){
                                                echo '<td width="2%" >№</td>';
                                            }
                                        ?>
                                        <td width="13%" >ФИО</td>
                                        <td width="13%">Подразделение</td>
                                        <td width="13%">Должность</td>
                                        <!-- <td width="4%">Телефон</td> -->
                                        <td width="5%">IP</td>
                                        <!-- <td width="8%">Имя компьютера</td> -->
                                        <!-- <td width="7%">Логин AD</td> -->
                                        <td width="7%">Логин БД</td>
                                        <td width="5%">Дата предоставления</td>
                                        <td width="5%">Дата окончания</td>
                                        <td width="5%">Дата выгрузки</td>
                                        <td width="6%">№ Заявки</td>
                                        <td width="7%">Статус</td>
                                        <td >Примечание</td>
                                        <td width="1%"> </td>
                                </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if($base){
                                    $requestionDAO = new RequestionDAO();
                                    $arrayRequestion = null;
                                    if ($select_imns == 0){
                                        $arrayRequestion = $requestionDAO->getActivRequestionByBaseRegion($bd, $base->id, $region->id);
                                    }else{
                                        $arrayRequestion = $requestionDAO->getActivRequestionByBaseImns($bd, $base->id, $imns->id);
                                    }
                                    
                                    foreach ($arrayRequestion as $requestion){
                                        $col = "";
                                        $col1 = "";
                                        echo "<tr>";
                                        $user = $usersDAO->getUsersById($bd, $requestion->id_user);
                                        $fio = "";
                                        $telefon = "";
                                        $ip = "";        
                                        if ($user){
                                            $fio = $user->fio;
                                            $telefon = $user->telefon;
                                            $ip = $user->ip;
                                        }
                                        $name = "";
                                        $unit = "";
                                        if ($user){
                                            $job = $jobsDAO->getJobsById($bd, $user->id_jobs);
                                            $name = $job->name;
                                            $unit_obj = $unitDAO->getUnitById($bd, $user->id_unit);
                                            $unit = $unit_obj->name;
                                        }
                                        $state = $requestion->state;
                                        if ( ($state=="выгружен на область") || ($state=="отправлен в МНС") ){
                                            $state = $state.', '.$requestion->request;
                                        }
                                        
                                        if ($user->active != 0){
                                            $col = " style='color:brown;' ";
                                        }
                                        if ( ($requestion->state == "прекращен") || ($requestion->state == "отказано") ){
                                            $col1 = " style='color:brown;' ";
                                        }else{
                                            if ($requestion->date_to){
                                                $tdata = date("d.m.Y",strtotime(_EXPIRE_DAY));
                                                $ndata = date("d.m.Y");
                                                $d1 = strtotime($tdata);
                                                $d2 = strtotime($requestion->date_to);
                                                $d3 = strtotime($ndata);

                                                if (($d1>$d2) && ($d2>=$d3)){
                                                    $col1 = " style='color:orange;' ";
                                                }
                                                if ($d3>$d2){
                                                     $col1 = " style='color:orange;' ";
                                                }
                                            }
                                        }
                                        
                                        
                                        if ($select_imns == 0){
                                            $imnsDAO = new ImnsDAO();
                                            $imnsBuf = $imnsDAO->getImnsById($bd, $requestion->id_imns);
                                            echo "<td ".$col.">".$imnsBuf->number."</td>";
                                        }
                                        echo "<td ".$col.">".$fio."</td>";
                                        echo "<td ".$col.">".$unit."</td>";
                                        echo "<td ".$col.">".$name."</td>";
                                        #echo "<td ".$col.">".$telefon."</td>";
                                        echo "<td ".$col.">".$ip."</td>";
                                        echo "<td ".$col1.">".$requestion->login."</td>";
                                        echo "<td ".$col1.">".convertDate($requestion->date_from)."</td>";
                                        echo "<td ".$col1.">".convertDate($requestion->date_to)."</td>";
                                        echo "<td ".$col1.">".convertDate($requestion->date_upload)."</td>";
                                        echo "<td>".$requestion->number."</td>";
                                        echo "<td ".$col1.">".$state."</td>";
                                        echo "<td ".$col1.">".$requestion->notice."</td>";
                                        if ($admins->id_access != 5){
                                            echo '<td><input class="button" type="button" onclick="edit_fild('.$requestion->id.')" value="✎" title="Изменить доступ"></td>';
                                        }	
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>            

            <form id="hides" method="post" autocomplete="off" target="_self" style="display:none" action="update_fild.php">
		<input type="text" id='id_fild' name="id_fild">
		<input type="submit" name='sub_id_fild'>
            </form>	
            
            <form id="avto_hides" method="post" autocomplete="off" target="_blank" style="display:none" action="config_avto.php">
		<input type="text" id='id_avto_base' name="id_avto_base">
		<input type="submit" name='sub_avto'>
            </form>
            
            <form id="local_avto_hides" method="post" autocomplete="off" target="_blank" style="display:none" action="config_local_avto.php">
		<input type="text" id='id_local_avto_base' name="id_local_avto_base">
		<input type="submit" name='sub_avto'>
            </form>
            
            <div id="userDialog" style="display:none;" title="Пользователи">
                <form id="userForm" onsubmit="return validateUserForm();" autocomplete="off" method="post" action="main.php">
                    <select id="userSelectId" name="userSelectId">
                        <option value="" selected>Новый пользователь</option>
                        <?php 
                            $usersList1 = $usersDAO->getUsersActiveList($bd, $imns->id, "0");
                            foreach ($usersList1 as $elem){
                                echo "<option value='".$elem->id."' style='background-color:#f0f'>".$elem->fio."</option>";
                            }
                        ?>
                    </select>
                    <p><select id="userUnit" name="userUnit">
                        <option value="">Выберите подразделение</option>
                        <?php 
                            foreach ($unitList as $unit){
                                echo "<option value='".$unit->id."'>".$unit->name."</option>";
                            }
                        ?>
                    </select></p>
                    <p><select id="userJobs" name="userJobs">
                        <option value="">Выберите должность</option>
                        <?php 
                            foreach ($jobsList as $jobs){
                                echo "<option value='".$jobs->id."'>".$jobs->name."</option>";
                            }
                        ?>
                    </select></p> 
                    <p><h8>Фамилия Имя Отчество: </h8><input type="text" id="userFio" name="userFio" required value="" title="Фамилия Имя Отчество" placeholder="Фамилия Имя Отчество" style="width:62%" autofocus/>
                    <p><h8>Рабочий телефон:</h8><h7>(8xxxxxxxxxx) </h7><input type="text" id="userTelefon" name="userTelefon" required value="" style="width:55%" pattern='8[0-9]{5,}' title="Рабочий телефон (8xxxxxxxxxx)" placeholder="Рабочий телефон (8xxxxxxxxxx)"/>
                    <p><h8>IP адрес:</h8><h7>(xxx.xxx.xxx.xxx) </h7><input type="text" required pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" id="userIP" name="userIP" title='IP адрес (xxx.xxx.xxx.xxx)' placeholder="IP адрес (xxx.xxx.xxx.xxx)" style="width:66%"/></p>
                    <p><input type="submit" id="userSubmit" name="userSubmit" value="Добавить" title="Сохранить"/>
					<br>__________<br><h7>*Для изменения сведений выберите пользователя, внесите необходимые изменения<br>и подтвердите свои действия.</h7><br>
                    <input type="submit" id="disableSubmit" name="disableSubmit" value="Деактивировать" style="display:none;" title="Сохранить"/></p>
                </form>  
                <form autocomplete="off" target="_blank" method="POST" action="user_log.php">
                    <input type="text" id="userLogId" name="userLogId" style="display:none">
                    <input type="submit" id="userLogsSubmit" name="userLogsSubmit" value="История изменений" style="display:none;" title="История изменений"/>
                </form>
                <br>
                <hr>
                <form autocomplete="off" target="_blank" method="POST" action="main.php">
                    <select name="userDisable">
                        <option value="">Выберите пользователя</option>
                        <?php 
                            $usersList = $usersDAO->getUsersActiveList($bd, $imns->id, "1");
                            foreach ($usersList as $elem){
                                echo "<option value='".$elem->id."'>".$elem->fio."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" id="activeSubmit" name="activeSubmit" value="Активировать" title="Сохранить"/>
                    <?php
                        if ($admins->id_access <= 3){
                            echo '<input type="submit" id="deleteUser" name="deleteUser" value="Удалить"/>';
                        }
                    ?>
                </form>
            </div>
            
            <div id="add_fild" style="display:none;"  title="Доступы">
                <form id="user_fild" name="user_fild" autocomplete="off" method="post" action="update_fild.php">
                    <h3>Корректировки:</h3>
                    <select id="select_user" name="select_user" >
                        <option value="0"  disabled selected>Выберите пользователя</option>
                        <?php 
                            $activUser = $usersDAO->getUsersActiveList($bd, $imns->id, 0);
                            foreach ($activUser as $user){
                                echo "<option value='".$user->id."'>".$user->fio."</option>";
                            }
                        ?>
                    </select>    
                    <p><select name="select_base" style="width:100%">
                        <option value="0" disabled selected>Выберите базу</option>
                        <?php 
                            foreach ($baseList as $base){
                                if ($base->archive != 0){
                                    continue;
                                }
                                echo "<option value='".$base->id."'>".$base->shot_name."</option>";
                            }
                        ?>
                    </select>
                    <p><h8>Примечание: </h8>
                    <p><textarea name="notice" title='Примечание' placeholder="Примечание" style="width:100%" autofocus/> </textarea></p>
                    <input type="submit" name="add" value="Добавить доступ" title="Добавить доступ">
                    <input type="submit" name="update" value="Изменить доступ" title="Изменить доступ">
                    <?php 
                        if ($admins->id_access == 3){
                            echo "<button name='requestion_log'>Лог</button>";
                        }
                    ?>
                </form>
                <hr>
                <form id="svod_form" autocomplete="off" target="_blank" method="GET" action="svod.php">
                    <h3>Сводная таблица:</h3>
                    <select id="selectUser" name="selectUser" >
                        <option value="0"  selected>Выберите пользователя</option>
                        <?php 
                            foreach ($activUser as $user){
                                echo "<option value='".$user->id."'>".$user->fio."</option>";
                            }
                        ?>
                    </select> 
                    <input type="submit" id="svod_button" value="Сформировать" title="Сформировать сводная таблица">
                    <br>__________<br><h7>*Для формирования сведений по всем работникам не заполняйте поле "Выберите пользователя"</h7>
                </form>
                <hr>
                <p><button id="close_fild" onclick="close_fild_click();">Прекращенный доступ</button>
		<button id="popup_alert_click" onclick="popup_alert_click();">Истекает доступ</button></p>
            </div>
            
            <div id="adminDialog" style="display:none;" title="Администрирование">
                <form id="adminForm" autocomplete="off" method="post" action="main.php">
                   <p><select id='adminsListId' name="adminsListId">
                       <option value=''>Выберите пользователя</option>
                        <?php 
                            $adminsDAO = new AdminsDAO();
                            $adminList = $adminsDAO->getAdminList($bd, $admins->id_imns, $admins);
                            foreach ($adminList as $elem){
                                echo "<option value='".$elem->id."'>".$elem->login."</option>";
                            }                     
                        ?>
                    </select>
                    <select id="adminAccessId" name="adminAccessId">
                        <option value="">Выберите доступ</option>
                        <?php 
                            $accessDAO = new AccessDAO();
                            $accessList = $accessDAO->getAccessList($bd);
                            foreach ($accessList as $elem){
                                if ($admins->id_access <= $elem->id){
                                    echo "<option value='".$elem->id."'>".$elem->name."</option>";
                                }
                            }
                        ?>
                    </select></p>
                    <select id="adminImnsListId" name="adminImnsListId">
                        <option value="">Выберите инспекцию</option>
                        <?php 
                            foreach ($imnsList as $elem){
                                echo "<option value=".$elem->id.">".$elem->name."</option>";
                            }                   
                        ?>
                    </select>
                    <input type="text" id="login" style="display:none"/>
                    <input type="password" id="fakepassword" style="display:none" />
                    <p><input type="text" required id="loginId" name="loginId" title='Логин' value='' placeholder="Логин" style="width:170px"/>
                    <input type="password"  required id="password1" name="password1" title='Пароль' value='' placeholder="Пароль" style="width:100px"/>
                    <input type="password" required id="password2" name="password2" title='Пароль' value='' placeholder="Пароль" style="width:100px"/>
                    <p><input type="text" id="smtp" name="smtp" title="SMTP xxx.xxx.xxx.xxx:port" value="" placeholder="SMTP xxx.xxx.xxx.xxx:port" style="width:470px"/>
                    <p><input type="submit" id="adminAddForm" name="adminAddForm" value="Создать" title="Сохранить"></p>
                    <hr>
                </form>
                <form id="imnsForm" name="imnsForm" autocomplete="off" method="post" action="main.php">
                    <?php
                        echo '<input type="text" required id="imnsFormId" name="imnsFormId" value="'.$imns->id.'" style="display:none" />';
                        echo '<input type="text" required id="imnsFormName" name="imnsFormName" value="'.$imns->name.'" title="Название инспекции" placeholder="Название инспекции" style="width:660px">';
                        echo '<p><input type="text" required id="imnsFormShotName" name="imnsFormShotName" value="'.$imns->shot_name.'" title="Сокращенное наименование инспекции" placeholder="Сокращенное наименование инспекции" style="width:660px"></p>';
                        echo '<p><input type="text" required id="imnsFormNumber" name="imnsFormNumber" value="'.$imns->number.'" title="Номер инспекции" placeholder="Номер инспекции" style="width:220px">';
                        echo '<input type="text" required id="imnsFormUnp" name="imnsFormUnp" value="'.$imns->unp.'" title="УНП" placeholder="УНП" style="width:220px">';
                        echo '<input type="text" required id="imnsFormPost" name="imnsFormPost" value="'.$imns->post.'" title="Почтовый индекс" placeholder="Почтовый индекс" style="width:220px">';
                        echo '<p><input type="text" required id="imnsFormMail" name="imnsFormMail" value="'.$imns->mail.'" title="Электронная почта" placeholder="Электронная почта" style="width:220px">';
                        echo '<input type="text"  id="imnsFormBik" name="imnsFormBik" value="'.$imns->bik.'" title="BIK" placeholder="BIK" style="width:220px">';
                        echo '<input type="text"  id="imnsFormScore" name="imnsFormScore" value="'.$imns->score.'" title="Расчетный счет" placeholder="Расчетный счет" style="width:220px"></p>';
                        echo '<input type="text"  id="imnsFormBank" name="imnsFormBank" value="'.$imns->bank.'" title="Наименование банка" placeholder="Наименование банка" style="width:220px">';
                        echo '<input type="text"  id="imnsFormBankAddress" name="imnsFormBankAddress" value="'.$imns->bank_address.'" title="Адрес банка" placeholder="Адрес банка" style="width:440px">';
                        echo '<p><input type="text" required id="imnsFormAddress" name="imnsFormAddress" value="'.$imns->address.'" title="Адрес инспекции" placeholder="Адрес инспекции" style="width:660px">';                     
                    ?>
                    <p><input type="submit" name="imnsAddForm" value="Сохранить" title="Сохранить"/></p>
                </form>
                <br>
                <?php 
                    if ($admins->id_access == 3){
                        echo '<hr>';
                        echo '<button id="regionButton" onclick="regionButtonClick();"> Региональные данные</button>';
                        echo '<button id="baseButton" onclick="baseButtonClick();">Базы данных</button>';
                    }
                ?>
            </div>
            
            <div id="regionDialog" style="display:none;" title="Региональные данные">
                <form id="jobsForm" name="jobsForm" autocomplete="off" method="post" action="main.php">
                    <select id="jobsId" name="jobsId">
                        <option value="">Выберите должность</option>
                        <?php                             
                            foreach ($jobsList as $jobs){
                                echo "<option value='".$jobs->id."'>".$jobs->name."</option>";
                            }
                        ?>
                    </select>    
                    <p><input type="text" id="nameJobs" name="nameJobs" value="" placeholder="Название должности" title="Название должности" style="width:480px" autofocus />
                    <p><input type="submit" id="saveJobs" name="saveJobs" value="Добавить" title="Сохранить"/>
                    <input type="submit" id="deleteJobs" name="deleteJobs" value="Удалить" title="Удалить" style="display:none;" /></p>
                </form>
                <form id="unitForm" autocomplete="off" method="post" action="main.php">
                    <select id="unitId" name="unitId">
                        <option value="">Выберите подразделение</option>
                        <?php 
                            foreach ($unitList as $unit){
                                echo "<option value='".$unit->id."'>".$unit->name."</option>";
                            }
                        ?>
                    </select>
                    <p><input type="text" id="nameUnit" name="nameUnit" value="" placeholder="Название подразделения" title="Название подразделения" style="width:480px">
                    <p><input type="submit" id="saveUnit" name="saveUnit" value="Добавить" title="Сохранить"/>
                    <input type="submit" id="deleteUnit" name="deleteUnit" value="Удалить" title="Удалить" style="display:none;" /></p>
                    <hr>
                </form>
            </div>
            
            <div id="baseDialog" style="display: none" title="Базы">
                <form id="baseForm" name="baseForm" autocomplete="off" method="post" accesskey="main.php">
                    <h3>Удаленные базы:</h3>
                    <select id="baseId" name="baseId">
                        <option value="">Выберите значение</option>
                        <?php 
                            foreach ($baseList as $elem){
                                if ($elem->archive != 0){
                                    continue;
                                }
                                echo "<option value='".$elem->id."'>".$elem->shot_name."</option>";
                            }
                        ?>
                    </select>
                    <p><input type="text" id="baseName" name="baseName" value="" placeholder="Полное наименование базы" title="Наименование базы" style="width:480px" autofocus/></p>
                    <p><input type="text" id="baseShotName" name="baseShotName" value="" placeholder="Короткое наименование базы" title="Короткое наименование" style="width:480px"/></p>
                    <p><input type="text" id="baseNotice" name="baseNotice" value="" placeholder="Примечание" title="Примечание" style="width:480px"/></p>
                    <p><input type="text" id="baseNewLogin" name="baseNewLogin" value="" placeholder="Автосоздание логина" title="Автосоздание логина" style="width:480px"/></p>
                    <p><input type="checkbox" id="baseArchive" name="baseArchive" /> <label for="archive">Архивная</label></p>
                    <input type="submit" id="saveBase" name="saveBase" value="Добавить" title="Сохранить"/>
                    <input type="submit" id="deleteBase" name="deleteBase" value="Удалить" title="Удалить" style="display:none" />
                    <input type="button" id="avtoButton" name = "avtoButton" value="Шаблон выгрузки" onclick="avtoButtonClick();" title="Автоматическое формирование" style="display: none"/>
                    <?php 
                        echo '<p><label><h8 style="width:480px">'.$newLoginNotice.'</h8></label></p>';
                        echo '<p><label><h8style="width:480px">'.$exempleLogin.'</h8></label></p>';
                    ?>
                    
                </form>
                <hr>
                <form id="localBaseForm" name="localBaseForm" autocomplete="off" method="post" action="main.php">
                    <h3>Локальные доступы:</h3>
                    <select id="localBaseId" name="localBaseId">
                        <option value="">Выберите значение</option>
                        <?php 
                            $localBaseList = $localBaseDao->getBaseListByRegion($bd, $region->id);
                            foreach ($localBaseList as $localBase){
                                echo "<option value='".$localBase->id."'>".$localBase->name."</option>";
                            }
                        ?>
                    </select>
                    <p><input type="text" id="localBaseName" name="localBaseName" value="" placeholder="Наименование локальной базы" title="Наименование локальной базы" style="width:480px"/></p>
                    <p><input type="text" id="localBaseNotice" name="localBaseNotice" value="" placeholder="Примечание" title="Примечание" style="width: 480px"></p>
                    <p><input type="checkbox" id="localBaseArchive" name="localBaseArchive" /> <label for="archive">Архивная</label></p>
                    <input type="submit" id="saveLocalBase" name="saveLocalBase" value="Добавить" title="Сохранить"/>
                    <input type="button" id="localAvtoButton" name = "localAvtoButton" value="Шаблон выгрузки" onclick="localAvtoButtonClick();" title="Автоматическое формирование" style="display: none"/>
                    <input type="submit" id="deleteLocalBase" name="deleteLocalBase" value="Удалить" title="Удалить" style="display:none" />
                </form>
            </div>

            <div id="reportDialog" style="display:none" title="Отчеты">
                <table><tr>
                    <td>
                        <div id="otchetall_div">
                            <?php 
                                echo'<input type="checkbox" name="check_all" />Выделить все';
                                echo'<form id="form_otchet" target="_blank"  method="post" action="otchetall.php">';
                                foreach ($baseList as $base) {
                                    if ($base->archive != 0){
                                        continue;
                                    }
                                    echo '<input type="checkbox" name="req[]" value="'.$base->id.'">'.$base->shot_name.'<BR>';
                                }
                                echo'<p><input type="submit" name="report" value="Отчет по доступу" title="Сформировать отчет"></p>';
                                echo'</form>';
                            ?>
                        </div>
                    </td>    
                    <td valign="top">    
                        <p><button id="unloaded_obl" onclick="unloaded_obl_click();"> Отчет по корректировкам</button></p>
                        <p><button id="localReport" onclick="JournalReportClick();" style="text-align: left;">Журнал учета (список) пользователей,<br>имеющих доступ к информационным ресурсам</button></p>

                        <?php
                            if (($admins->id_access == 3)){
                                echo '<p><button id="count" onclick="count_click();"> Количество представленных доступов</button></p>';
                                echo '<p><button id="count" onclick="expired_click();"> Прекращенные доступы старше 5 лет</button></p>';
                            }
                            echo '<p><button id="localReport" onclick="localReportClick();">Локальные доступы (+/-)</button></p>';
                            echo '<p><button id="localReport" onclick="localReport2Click();">Локальные доступы (с датами)</button></p>';
                        ?>
                    </td>   
                </tr></table>
            </div>
            
            <div class="popup_alert" title="В течении 2-х месяцев доступ заканчивается:" id="popup_date">
		<div class="popup-content">
                    <table border="1" width="100%" cellpadding="1" style="overflow-y: scroll;" cols="14" class="sortable" >
                        <thead>
                            <tr>
                                <td width="3%">Код ИМНС</td>
                                <td width="13%" >ФИО</td>
                                <td width="3%">Дата окончания</td>
                                <td width="10%">Статус</td>
                                <td width="10%">ИР</td>
                            </tr>
                        </thead>
                        <tbody class="table_body">
                            <?php 
                                $requestionDAO = new RequestionDAO();
                                $arrayRequestion = null;
                                if ($select_imns == 0){
                                    $arrayRequestion = $requestionDAO->getRequestionByRegion($bd, $region->id);
                                }else{
                                    $arrayRequestion = $requestionDAO->getRequestionByImns($bd, $imns->id);
                                }
                                
                                foreach ($arrayRequestion as $requestion){
                                    $tdata = date("d.m.Y",strtotime(_EXPIRE_DAY));
                                    $ndata = date("d.m.Y");
                                    if ($requestion->date_to){
                                        $d1 = strtotime($tdata);
                                        $d2 = strtotime($requestion->date_to);
                                        $d3 = strtotime($ndata);
                                        
                                        if (($d1>$d2) || ($d2<=$d3)){
                                            $imnsNumber="";
                                            foreach ($imnsList as $elem){
                                            if ($elem->id == $requestion->id_imns){
                                                    $imnsNumber = $elem->number;
                                                }
                                            }
                                            $irName="";
                                            foreach ($baseList as $base){
                                                if ($base->id == $requestion->id_base){
                                                    $irName = $base->shot_name;
                                                }
                                            }
                                            if ($requestion->state == $fild_state[3]){
                                                continue;
                                            }
                                            $userBuf = $usersDAO->getUsersById($bd, $requestion->id_user);
                                            echo "<tr>";
                                            echo "<td>".$imnsNumber."</td>";
                                            echo "<td>".$userBuf->fio."</td>";
                                            echo "<td>".$requestion->date_to."</td>";
                                            echo "<td>".$requestion->state."</td>";
                                            echo "<td>".$irName."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
		</div>
            </div>
            
            
	</body>
        <script>
            let dataDiv = document.getElementById("otchetall_div");
            let checkItAll= dataDiv.querySelector("input[name='check_all']");
            var inputs = dataDiv.querySelectorAll('form>input');
            
            $('#userSelectId').selectize();
            let selectUnit = $('#userUnit').selectize();
            let selectJobs = $('#userJobs').selectize();
            $('#jobsId').selectize();
            $('#baseId').selectize();
            $('#base').selectize();
            $('#select_user').selectize();
            
            
            $("#userDialog").dialog({
                    autoOpen: false,
                    width: 'auto'
            });
            
            $("#adminDialog").dialog({
                autoOpen: false,
                width: 'auto'
            });
            
            $("#regionDialog").dialog({
                autoOpen: false,
                width: 'auto'
            });
            
            $("#baseDialog").dialog({
                autoOpen: false,
                width: 'auto'
            });
            
            $( "#add_fild" ).dialog({
                    autoOpen: false,
                    width: 500
            });
            
            $( "#reportDialog" ).dialog({
                    autoOpen: false,
                    width: 600
            });
            
            $("#popup_date").dialog({
                autoOpen:false,
                width: 800,
                height: "auto"
            });
            
            if (checkItAll){
                checkItAll.addEventListener('change', function(){
                    if (checkItAll.checked){
                        Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=true;});
                    }else{
                        Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=false;});
                    }
                });
            }
            
            $(function(){
                var ses = $.session.get('start');
                //alert(ses);
                if (ses!=='0'){
                        $.session.set('start','0');
                        $("#popup_date").dialog("open");
                }
            });
            
            $(document).ready(function(){
         
                $('#adminsListId').on('change', function(){
                    var adminListId = $(this).val();
                    if (adminListId){
                        $.ajax({
                            type: 'POST',
                            url: 'otherPHP/ajaxAdmin.php',
                            dataType: 'json',
                            data: 'adminsListId='+adminListId,
                            success: function(json){
                                document.getElementById('loginId').value=json[0].login;
                                document.getElementById('adminImnsListId').value=json[0].id_imns;
                                document.getElementById('adminAccessId').value=json[0].id_access;
                                document.getElementById('password1').value=json[0].password;
                                document.getElementById('password2').value=json[0].password;
                                document.getElementById('smtp').value=json[0].smtp; 
                                document.getElementById('adminAddForm').value="Сохранить";
                            }
                        });
                    }else{
                        document.getElementById('loginId').value="";
                        document.getElementById('adminImnsListId').value="";
                        document.getElementById('adminAccessId').value="";
                        document.getElementById('password1').value="";
                        document.getElementById('password2').value="";
                        document.getElementById('smtp').value="";
                        document.getElementById('adminAddForm').value="Создать";
                    }
                });
                
                $('#unitId').on('change', function(){
                    var unitId = $(this).val();
                    if (unitId){
                        $.ajax({
                            type: 'POST',
                            url: 'otherPHP/ajaxUnit.php',
                            dataType: 'json',
                            data: 'unitId='+unitId,
                            success: function(json){
                                document.getElementById('nameUnit').value=json[0].name;
                                document.getElementById('deleteUnit').style.display="inline";
                                document.getElementById('saveUnit').value='Сохранить';
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('nameUnit').value="";
                        document.getElementById('deleteUnit').style.display="none";
                        document.getElementById('saveUnit').value='Добавить';
                    }
                });
                
                $('#jobsId').on('change', function(){
                    var unitId = $(this).val();
                    if (unitId){
                        $.ajax({
                            type: 'POST',
                            url: 'otherPHP/ajaxUnit.php',
                            dataType: 'json',
                            data: 'jobsId='+unitId,
                            success: function(json){
                                document.getElementById('nameJobs').value=json[0].name;
                                document.getElementById('deleteJobs').style.display="inline";
                                document.getElementById('saveJobs').value='Сохранить';
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('nameJobs').value="";
                        document.getElementById('deleteJobs').style.display="none";
                        document.getElementById('saveJobs').value='Добавить';
                    }
                });    
                
                $('#userSelectId').on('change', function(){
                    console.log('userselectid');
                    var userId = $(this).val();
                    if (userId){
                        $.ajax({
                           type: 'POST',
                           url: 'otherPHP/ajaxUsers.php',
                           dataType: 'json',
                           data: 'userId='+userId,
                           success: function(json){
                               console.log(json[0]);
                               document.getElementById('userFio').value=json[0].fio;
                               document.getElementById('userTelefon').value=json[0].telefon;
                               document.getElementById('userIP').value=json[0].ip;
                               document.getElementById('userJobs').value=json[0].id_jobs;
                               document.getElementById('userUnit').value=json[0].id_unit;
                               let controlJobs = selectJobs[0].selectize;
                               controlJobs.setValue(json[0].id_jobs);
                               let controlUnit = selectUnit[0].selectize;
                               controlUnit.setValue(json[0].id_unit);
                               document.getElementById('userSubmit').value='Сохранить';
                               let active = 'Деактивировать';
                               //console.log(json[0].id_unit);
                               if (json[0].active == 0){
                                   active = 'Деактивировать';
                               }else{
                                   active = 'Активировать';
                               }
                               document.getElementById('disableSubmit').value=active;
                               document.getElementById('disableSubmit').style.display='inline';
                               document.getElementById('userLogsSubmit').style.display='inline';
                               document.getElementById('userLogId').value = json[0].id;
                           },
                           error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('userFio').value="";
                        document.getElementById('userTelefon').value="";
                        document.getElementById('userIP').value="";
                        document.getElementById('userJobs').value="";
                        document.getElementById('userUnit').value="";
                        document.getElementById('userSubmit').value='Добавить';
                        document.getElementById('disableSubmit').style.display='none';
                        document.getElementById('userLogsSubmit').style.display='none';
                        document.getElementById('userLogId').value = "";
                    }
                });
                
                $('#baseId').on('change', function(){
                    var baseId= $(this).val();
                    if(baseId){
                        $.ajax({
                            type: 'POST',
                            url: 'otherPHP/ajaxBase.php',
                            dataType: 'json',
                            data: 'baseId='+baseId,
                            success: function(json){
                                document.getElementById('baseName').value=json[0].name;
                                document.getElementById('baseShotName').value=json[0].shot_name;
                                document.getElementById('baseNewLogin').value=json[0].new_login;
                                document.getElementById('baseNotice').value=json[0].notice;
                                if (json[0].archive == 0){
                                    document.getElementById('baseArchive').checked = false;
                                }else{
                                    document.getElementById('baseArchive').checked = true;
                                }
                                document.getElementById('saveBase').value='Сохранить';
                                document.getElementById('deleteBase').style.display='inline';
                                document.getElementById('avtoButton').style.display='inline';
                                document.getElementById('id_avto_base').value = json[0].id;
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('baseName').value="";
                        document.getElementById('baseShotName').value="";
                        document.getElementById('baseNewLogin').value="";
                        document.getElementById('baseNotice').value="";
                        document.getElementById('baseArchive').checked = false;
                        document.getElementById('saveBase').value='Добавить';
                        document.getElementById('deleteBase').style.display='none';
                        document.getElementById('avtoButton').style.display='none';
                        document.getElementById('id_avto_base').value = "";
                    }
                });
                
                $('#localBaseId').on('change', function(){
                    var baseId= $(this).val();
                    if(baseId){
                        $.ajax({
                            type: 'POST',
                            url: 'otherPHP/ajaxLocalBase.php',
                            dataType: 'json',
                            data: 'baseLocalId='+baseId,
                            success: function(json){
                                document.getElementById('localBaseName').value=json[0].name;
                                document.getElementById('localBaseNotice').value=json[0].notice;
                                if (json[0].archive == 0){
                                    document.getElementById('localBaseArchive').checked = false;
                                }else{
                                    document.getElementById('localBaseArchive').checked = true;
                                }
                                document.getElementById('saveLocalBase').value='Сохранить';
                                document.getElementById('id_local_avto_base').value = json[0].id;
                                document.getElementById('localAvtoButton').style.display='inline';
                                document.getElementById('deleteLocalBase').style.display='inline';
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('localBaseName').value="";
                        document.getElementById('localBaseNotice').value="";
                        document.getElementById('localBaseArchive').checked = false;
                        document.getElementById('saveLocalBase').value='Добавить';
                        document.getElementById('id_local_avto_base').value = "";
                        document.getElementById('deleteLocalBase').style.display='none';
                        document.getElementById('localAvtoButton').style.display='none';
                    }
                });
                
            });
        </script>
            
</html>