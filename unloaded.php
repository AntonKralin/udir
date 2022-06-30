<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
$base = null;
$requestionDAO = new RequestionDAO();

if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
$_SESSION["HTTP_REFERER"] = 'unloaded.php';
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


if (isset ($inputs["subupdate"])){
    if (isset ($inputs["check"])){
        $checked = $inputs["check"];
        foreach ($checked as $check){
            $requestionDAO->updateState($bd, $check, "отправлен в МНС");
        }
    }
}

$usersDAO = new UsersDao();
$jobsDAO = new JobsDAO();
$jobsList = $jobsDAO->getJobsList($bd, $region->id);
$baseDAO = new BaseDAO();
$baseList = $baseDAO->getBaseList($bd, $region->id);

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
            <script type="text/javascript" src="js/functions.js?8"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/sort.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
	</head>
        
        
	<body>
            <div id="top_head">
                <div id="top_left">
                    <?php 
                        
                            echo '<form id="imns_label" method="post" autocomplete="off" action="unloaded.php">';
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
                <!--    <?php echo "<H9><<УДИР>> ".$region->name."</H9>" ?>  -->
				<a href="main.php" class="ahead">«Учет доступа к информационным ресурсам». </a><H9 style="color: white;">Корректировки.</H9>
                </div>
                    
            </div>
            
            <div id="next_top_head">
                <form id="menu_base" method="post" autocomplete="off" action="unloaded.php">
                    <select id="base_click" name="base_click" style='font-weight:bold;' onchange="document.getElementById('menu_base').submit();">
                        <?php 
                            foreach ($baseList as $elem ){
                                $val = "";
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
                        <?php 

                            if ($admins->id_access == "3"){
                                echo '<input type="button" name="updates" value="Отправить в МНС" title="отправить" onclick="unload_to_mns()">';
                                //echo '<input type="button" name="create" onclick="check_click()" value="Сформировать отчет" title="Сформировать отчет">';

                            }
                            echo '<input type="button" name="createavto" onclick="create_click()" value="Сформировать автоматически" title="Сформировать автоматически">';
                        ?>
                    </div>
            </div>
      
            <form method="post" autocomplete="off" action="unloaded.php">  
                <div id="work">
                    <!-- таблица-->
                    <table id="date-table" border="1" width="100%" cellpadding="1" cols="12" class="sortable" >
                        <thead>
                                <tr>    
                                        <td windth="2%">ИМНС</td>
                                        <td width="13%" >ФИО</td>
                                        <td width="13%">Должность</td>
                                        <td width="4%">Телефон</td>
                                        <td width="5%">IP</td>
                                        <td width="7%">Логин БД</td>
                                        <td width="7%">Статус</td>
                                        <td >Примечание</td>
                                        <?php 
                                            if ($admins->id_access ==3){
                                                echo '<td name="undefined" width="3%"><input type="checkbox" name="check_all" />Выгрузка </td>';
                                            }
                                        ?>

                                </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if($base){

                                    $arrayRequestion = null;
                                    if ($select_imns == 0){
                                        $arrayRequestion = $requestionDAO->getRequestionByBaseStateRegion($bd, $base->id, 'выгружен на область' , $region->id);
                                    }else{
                                        $arrayRequestion = $requestionDAO->getRequestionByBaseStateImns($bd, $base->id, 'выгружен на область', $imns->id);
                                    }

                                    foreach ($arrayRequestion as $requestion){
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
                                        if ($user){
                                            $job = $jobsDAO->getJobsById($bd, $user->id_jobs);
                                            $name = $job->name;
                                        }                                        

                                        $imnsDAO = new ImnsDAO();
                                        $imnsBuf = $imnsDAO->getImnsById($bd, $requestion->id_imns);

                                        echo "<td>".$imnsBuf->number."</td>";
                                        echo "<td>".$fio."</td>";
                                        echo "<td>".$name."</td>";
                                        echo "<td>".$telefon."</td>";
                                        echo "<td>".$ip."</td>";
                                        echo "<td>".$requestion->login."</td>";
                                        echo "<td>".$requestion->request."</td>";
                                        echo "<td>".$requestion->notice."</td>";
                                        if ($admins->id_access == 3){
                                            $form = '<input class="button" type="button" onclick="edit_fild('.$requestion->id.')" value="✎" title="Изменить доступ">';
                                            echo "<td><input type='checkbox' name='check[]' value=".$requestion->id.">".$form."</td>";
                                        }	
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <input type="submit" id="subupdate" name="subupdate" value="update" style="display:none">
            </form>

            
            <form id="hides" method="post" autocomplete="off" target="_self" style="display:none" action="update_fild.php">
		<input type="text" id='id_fild' name="id_fild">
		<input type="submit" name='sub_id_fild'>
            </form>
                
            <form id="hidesotchet" method="post" autocomplete="off" target="_blank" style="display:none" action="otchet.php">
                <input type="text" id='id_base' name="sub_create" value="1">
                <input type="submit" id='sub_hides2' name='sub_creates'>
            </form>    
                
            <div id="check" style="display:none" title="Формирование отчета">
                <form id="update_user_form" target="_blank"  method="post" action="otchet.php">
                    <?php 
                            $table_request = "<p><select name='request'>";
                            foreach($request_state as $buf){
                                    $table_request .= "<option value='".$buf."' >".$buf."</option>";
                            }					
                            $table_request .= "</select></p>";
                            echo $table_request;
                    ?>
                    <div></div>
                    <input type="checkbox" name="req[]" value="number">Код ИМНС<BR>
                    <input type="checkbox" name="req[]" value="name">Название ИМНС<BR>
                    <input type="checkbox" name="reqIMNS" value="`number`, `name`">Название ИМНС (Код ИМНС)<BR>
                    <input type="checkbox" name="req[]" value="unit">Подразделение<BR>
                    <input type="checkbox" name="req[]" value="job">Должность<BR>
                    <input type="checkbox" name="req[]" value="fio">ФИО<BR>
                    <input type="checkbox" name="reqJob" value="`job`, `fio`">Должность, ФИО<BR>
                    <input type="checkbox" name="reqUnit" value="`unit`, `job`, `fio`">Подразделение, должность, ФИО<BR>
                    <input type="checkbox" name="req[]" value="telefon">Телефон<BR>
                    <input type="checkbox" name="req[]" value="ip">IP<BR>
                    <input type="checkbox" name="req[]" value="ad_login">Логин AD<BR>
                    <input type="checkbox" name="req[]" value="login">Логин БД<BR>
                    <p><input type="submit" name="report" value="Сформировать" title="Сформировать отчет"></p>
                </form>
		</div>                
            
	</body>  
        <script>
            var dataTable = document.getElementById("date-table");
            var checkItAll= dataTable.querySelector("input[name='check_all']");
            var inputs = dataTable.querySelectorAll('tbody>tr>td>input');

            function unload_to_mns(){
                let but = document.getElementById('subupdate');
                but.click();
            }

            checkItAll.addEventListener('change', function(){
                    if (checkItAll.checked){
                            //inputs.forEach(function(input){
                            //	input.checked = true;
                            //})
                            Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=true;});

                    }else{
                            //inputs.forEach(function(input){
                            //	input.checked = false;
                            //})
                            Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=false;});
                    }
            });
            
            $( "#check" ).dialog({
                autoOpen: false,
                width: 500
            });
        </script>
</html>

