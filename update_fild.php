<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$base   = unserialize($_SESSION['base']);
$bd = new BD();
}else{
    exit;
}

$inputs = filter_input_array(INPUT_POST);

$requestionDAO = new RequestionDAO();
$requestion = null;
$user = null;
if (isset($inputs["id_fild"])){ 
    $requestion = $requestionDAO->getRequestionById($bd, $inputs["id_fild"]);
    $userDAO = new UsersDao();
    $user = $userDAO->getUsersById($bd, $requestion->id_user);
}

//if (isset ($inputs['select_base'])){
//    $requestion = $requestionDAO->getRequestionById($bd, $inputs["select_base"]);
//    $userDAO = new UsersDao();
//    $user = $userDAO->getUsersById($bd, $requestion->id_user);    
//}

if (isset ($inputs['update'])){
    $requestion = $requestionDAO->getRequestionByUserBase($bd, $inputs["select_user"], $inputs["select_base"]);
    if ($requestion != null){
        $userDAO = new UsersDao();
        $user = $userDAO->getUsersById($bd, $requestion->id_user);    
    }
}

if (isset($inputs['upd_fild'])){
    $requpd = $requestionDAO->getRequestionById($bd, $inputs['id']);
    $date_upload = $requpd->date_upload;
    if ($inputs['state']==$fild_state[0]){
            $date_upload = date("Y-m-d");
    }
    echo $date_upload;
    $requestionDAO->updateFild2($bd, $inputs['id'], $inputs['login'], $inputs['date_from'], $inputs['date_to'], $date_upload, $inputs['state'], $inputs['request'], $inputs['number'],$inputs['notice'], $admins->id);
    header("Location: ".$_SESSION["HTTP_REFERER"]);
    exit;
}

if (isset($inputs['del_fild'])){
    $requestionDAO->deleteFild($bd, $inputs['id']);
    header("Location: ".$_SESSION["HTTP_REFERER"]);
    exit;
}

if (isset($inputs['add'])){
    $id_user = $inputs['select_user'];
    $id_base = $inputs['select_base'];
    $number = $inputs['number'];
    $notice = $inputs['notice'];
    $date_upload = date("Y-m-d");
    $login = $requestionDAO->createNewLogin($bd, $imns->id, $id_user, $id_base);
    $count = $requestionDAO->getCountBaseUser($bd, $id_base, $id_user);
    if ($count == 0){
        $requestionDAO->insertFild2($bd, $id_user, $id_base, $imns->id, $login, $date_upload, $fild_state[0], $request_state[0], $number, $notice, $admins->id);
    }
    //echo '<script>window.close()</script>';
    header("Location: ".$_SESSION["HTTP_REFERER"]);
    //exit;
    
}

if (isset($inputs['requestion_log'])){
    $id_user = $inputs['select_user'];
    $id_base = $inputs['select_base'];
//    $url = '';
//    $data = array("id_user"=> "", 'id_base'=>"");
//    $options = array(
//        'http' => array(
//            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
//            'method' => 'POST',
//            'content' => http_build_query($data)
//        )
//    );
//    $context = stream_context_create($options);
//    $result = file_get_contents($url,false, $context);
    header('Location: requestion_log.php?id_user='.$id_user.'&id_base='.$id_base);
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
            <link rel="stylesheet" href="styles/main.css?5" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?6"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
            <script type="text/javascript"> 
                function validate_form(){
                        var valid = true;
                        if ( (document.getElementById("state").value=="действующий") && (document.getElementById("date_from").value=="") ){
                                alert("Введите дату предоставления");
                                valid=false;
                        }
                        if ( (document.getElementById("state").value=="прекращен") && (document.getElementById("date_to").value=="") ){
                                alert("Введите дату прекращения");
                                valid=false;
                        }
                        return valid;
                }
            </script>
            </script>
	</head>

	<body>
            <div class ="body_div">
                <div class ="center_div">
                    <?php 
                        if (($requestion != null) && ($user != null)){
                            $baseDAO = new BaseDAO();
                            $base = $baseDAO->getBaseById($bd, $requestion->id_base);
                            $count = $requestionDAO->getActiveCount($bd, $base->id, $imns->id);
                            echo "<H3>Количество действующих учеток в имнс:".$count."</H3>";
                            echo "<H2>".$base->shot_name."</H2>";
                            echo "<H2>".$user->fio."</H2>";
                            
                            $certificationDAO = new CertificateDAO();
                            $certificationList = $certificationDAO->getCertificateByUserState($bd, $user->id, "действующий");
                            foreach ($certificationList as $cert){
                                $str = "Выдан ".$cert->name." ".convertDate($cert->date_from)." ";
                                echo "<div style='display:flex; width:100%'><H2 style='width:65%;'>".$str."</H2>";
                                echo "<button onclick='setFildDateFrom(\"".$cert->date_from."\");' style='width: 17%;margin-top: 15;height: 30;'>Подтянуть С</button>";
                                echo "<button onclick='setFildDateTo(\"".$cert->date_to."\");' style='width: 17%;margin-top: 15;height: 30;'>Подтянуть По</button>";
                                echo "</div>";
                            }
                            
                            echo '<form class="user_update_form" onsubmit="return validate_form();" autocomplete="off" method="post" action="update_fild.php">';
                            echo "<p><label><h8>Логин:</h8></label><input type='text' placeholder='Логин' title='Логин информационного ресурса' name='login' value='".$requestion->login."'></p>";
                            echo "<p><label><h8>№ заявки:</h8></label><input type='text' placeholder='№ заявки' title='№ заявки информационного ресурса' name='number' value='".$requestion->number."'></p>";
                            echo "<p><label><h8>Дата предоставления доступа:</h8></label><input type='text' readonly placeholder='Дата предоставления доступа' title='Дата предоставления' onclick='choose_data(this);' id='date_from' name='date_from' id='date_from' value='".$requestion->date_from."'></p>";
                            echo "<p><label><h8>Дата прекращения (окончания) доступа:</h8></label><input type='text' readonly placeholder='Дата прекращения (окончания) доступа' title='Дата прекращения' onclick='choose_data_to(this);' id='date_to' name='date_to' id='date_to' value='".$requestion->date_to."'></p>";
                            $table_select = "<p><label><h8>Статус:</h8></label><select onchange='check_select(this.value)' title ='Статус' id='state' name='state'>";
                            $displaynone= "style='display:none;'";
                            $dis = "";
                            foreach($fild_state as $buf){
                                    $str = "";
                                    if ( $buf == $requestion->state ) {
                                        $str='selected="selected"';  
                                        if ( ($buf==$fild_state[1]) || ($buf==$fild_state[0])){
                                            $displaynone=" ";
                                        }
                                    }
                                    if (($admins->id_access > 3) && ( $buf == $fild_state[1])){
                                        $table_select .= "<option value='".$buf."' ".$str." disabled >".$buf."</option>";
                                        if ( $buf == $requestion->state ) {
                                            $dis=" disabled ";
                                        }
                                    }else{
                                        $table_select .= "<option value='".$buf."' ".$str.">".$buf."</option>";
                                    }
                            }
                            $table_select .= "</select></p>";
                            echo $table_select;
                            $table_request = "<p><select id='request' name='request' title='".$requestion->request."' ".$displaynone.$dis.">";
                            foreach($request_state as $buf){
                                    $str = "";
                                    if ( $buf == $requestion->request){ 
                                            $str='selected="selected"';
                                    }
                                    $table_request .= "<option value='".$buf."' ".$str.">".$buf."</option>";
                            }					
                            $table_request .= "</select></p>";
                            echo $table_request;
                            echo "<label><h8>Примечание:</h8></label><textarea placeholder='Примечание' title='Примечание' name='notice'>".$requestion->notice."</textarea>";
                            echo "<input type='text' name='id' style='display:none' value='".$requestion->id."'>";
                            echo '<hr><p><H8 style="color:brown">!!! Обратите внимание !!!</H8><h7><br>1) После получения ответов из МНС, необходимо внести данные в соответствующие поля.<br>2) При смене должности, в «Редактировании пользователя» указываете новую должность, сохраняете. Затем, в тех базах, где это предусмотрено и был доступ, необходимо проставить статус "выгружен на область, изменить реквизиты", а в поле «Примечание» проставить "дату изменения должности" и сделать запись: "смена должности". После получения ответа из МНС, сделать статус "действующий", поле «Примечание» очистить от соответствующей записи.<br>3) Если в сведениях из МНС не указана дата предоставления доступа, следует указать дату фактического получения письма.<br>4) Датой прекращения доступа для баз СККС, СКТА, СККО, БЭСТ считается дата получения статуса "отправлено в МНС", для остальных баз - дата получения ответа из МНС.<br>5) Для баз АС ГАИ-Центр, ЕГБДП, АС Паспорт - в поле "Дата прекращения (окончания) доступа" вносится дата, указанная в самой базе, для своевременного продления.</h7><br><br></p>';
                            echo '<p><input type="submit" class="button" name="upd_fild" value="Сохранить изменения" title="Сохранить изменения"></p>';
                            if ($admins->id_access <= 3 ){
                                    echo '<p><input type="submit" class="button" name="del_fild" value="Удалить" title="Удаление заявки с БД"/></p>';
                            }
                            echo '</form>';
                        }
                        $go = "go_to_main_click('".$_SESSION["HTTP_REFERER"]."')";
			echo '<p><button class="button" onclick="'.$go.'" title="Выйти на главную страницу">Назад</button></p>';
                    ?>
                    <br>
                </div>
            </div>
	
	</body>
	<script>
                function choose_data(elem){
			//$.datepicker.setDefaults($.datepicker.regional['ru']);
			$(elem).datepicker({
				changeYear: true,
				dateFormat: "yy-mm-dd",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                                firstDay: 1
			});
                        $(elem).datepicker("option", $.datepicker.regional["fr"]);
			$(elem).datepicker("show");
		}
		
                function setFildDateFrom(date){
                    document.getElementById("date_from").value=date;
                }
                
                function setFildDateTo(date){
                    document.getElementById("date_to").value=date;
                }
                
		function choose_data_to(elem){
			$(elem).datepicker({
				showButtonPanel: true,
				beforeShow: function( input ){
					setTimeout( function(){
						var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
						$("<button>",{
							text: "Clear",
							click: function(){
								$.datepicker._clearDate(input);
							}
						}).appendTo(buttonPane).addClass("uit-datepicker-clear ui-state-default ui-priority-prime ui-corner-all");
					},1);
				},
				changeYear: true,
				dateFormat: "yy-mm-dd",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                                firstDay: 1
			});	
			$(elem).datepicker("show");
		}
	</script>	
</html>

