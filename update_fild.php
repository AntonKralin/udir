<?php
session_start();
include 'connect.php';
include 'const.php';
$id_access = null;
$id_mns = $id_access;

function getFIO($link, $id_user){
	$user_query = "SELECT * FROM `table_user` WHERE `id` = ".mysqli_real_escape_string($link, $id_user);
		$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
		$users=[];
		for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
		$fio = "";
		foreach($users as $elem){
			$fio = $elem['fio'];
		}
	return $fio;
}

function getCountLoginBase($link, $user, $base, $login){
	$user_query = "SELECT count(*) FROM `table_fild` WHERE `id_user`= ".mysqli_real_escape_string($link, $user)." and `id_base`=".mysqli_real_escape_string($link, $base)." and `login`='".mysqli_real_escape_string($link, $login)."'";
	$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
	$users=[];
	for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
	$count = "";
	foreach($users as $elem){
		$count = $elem['count(*)'];
	}
	return $count;
}

function getCountBaseMns($link, $imns, $base){
	$user_query = "SELECT count(*) FROM `table_fild` WHERE `id_mns`= ".mysqli_real_escape_string($link, $imns)." and `id_base`=".mysqli_real_escape_string($link, $base);
	$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
	$users=[];
	for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
	$count = "";
	foreach($users as $elem){
		$count = $elem['count(*)'];
	}
	return $count+1;
}

function getIP($link, $id_user){
	$user_query = "SELECT * FROM `table_user` WHERE `id` = ".mysqli_real_escape_string($link, $id_user);
		$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
		$users=[];
		for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
		$ip = "";
		foreach($users as $elem){
			$ip = $elem['ip'];
		}
		
		$buf = explode(".", $ip);
		while (iconv_strlen($buf[3])<3){
			$buf[3] = "0".$buf[3];
		}
	return $buf[3];
}

function getIMNS($link, $id_imns){
	$mns_query = 'select * from `table_imns` '."where `id`= ".mysqli_real_escape_string($link, $id_imns);
		$result = mysqli_query($link,$mns_query) or die(mysqli_error($link));
		$imns=[];
		$imns=0;
		for ($imns=[]; $row=mysqli_fetch_assoc($result); $imns[]=$row);
		foreach($imns as $elem){
			$imns= $elem['number'];
		}
		
	return $imns;
}

function translate1($imns, $fio){
	$login = "";
	$login = $imns;
	$buf = explode(" ", $fio);
	$fio = $buf[0]."_";
	for ($i=1;$i<count($buf);$i++){
		$fio .= " ".mb_substr($buf[$i],0,1);
	}
	$login = $login.transliteration($fio);
	
	return $login;
}



function translate2($imns, $fio){
	$login = "";
	//$login = $login.$imns;
	$buf = explode(" ", $fio);
	$fio = "000";
	for ($i=0;$i<count($buf);$i++){
		$fio .= " ".mb_substr($buf[$i],0,1);
	}
	$login = transliteration($fio);
	
	return $login;
}

function translate3($fio){
	$buf = explode(" ", $fio);
	
	return transliteration($buf[0]);
}

function checkLoginCount($link, $user, $base, $login, $count){
	$result = $login;
	if( getCountLoginBase($link, $user, $base, $login) != 0 ){
		$result = checkLoginCount($link, $user, $base, $login, $count+1);
		if ( stristr($result,'_') === FALSE){
			$result .= "_".$count;
		}
	}

	return $result;
}


if (isset ($_SESSION["id_access"])){
	$id_access = $_SESSION["id_access"];
}else{
	exit;
}

if (isset ($_POST["imns_id"])){
	$id_mns = $_POST["select_menu"];
	$_SESSION["id_mns"] = $id_mns;
	//echo "a".$id_mns;
}

if (isset ($_SESSION["id_mns"])){
	$id_mns = $_SESSION["id_mns"];
}

if (isset($_POST['add'])){
	$count_query = "select count(*) from `table_fild` where `id_user`=".mysqli_real_escape_string($link, $_POST["select_user"])." and `id_base`=".mysqli_real_escape_string($link, $_POST["select_base"]);
	$rez_count = mysqli_query($link, $count_query) or die (mysqli_error($link));
	//var_dump($rez_count);
	$count=[];
	$con = 0;
	for ($count=[]; $row=mysqli_fetch_assoc($rez_count); $count[]=$row);
	foreach ($count as $elem)
		$con = $elem["count(*)"];
	if (($con > 0) && ($id_access!=0)){
		echo "<script>alert('В БД уже есть запись с этим пользователем')</script> ";
		//header("Location: main.php");
		//header("refresh:5;url=main.php");
		//echo 'В БД уже есть запись с этим пользователем';
	}else{
		$login = "";
		
		if ( $_POST["select_base"] == 6) 
			{
				$login = translate1(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"]));
				echo $login;
			}
		switch($_POST["select_base"]){
			case '0': break; 
			case '1': break;
			case '2': break;
			case '3': 	$login = "MNC_";
						break;
			case '4': 	$login =  getIMNS($link, $id_mns).getIP($link, $_POST["select_user"]);
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '5': break;
			case '6': 	$login = translate1(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"])); 
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '7': 	$login = "VIT";
						break;
			case '8': 	$login = translate1(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"])); 
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '9': 	break;
			case '10': 	$login = translate1(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"])); 
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '11': 	$login = translate1(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"])); 
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '12': 	$login = "VIT".getIMNS($link, $id_mns)."N".getCountBaseMns($link,$id_mns,$_POST["select_base"]);
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '13': 	$login = "NLA".getIMNS($link, $id_mns)."VI".getIP($link, $_POST["select_user"]);
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '14': break;
			case '15': break;
			case '16': 	$login = translate2(getIMNS($link, $id_mns), getFIO($link, $_POST["select_user"])).getIMNS($link, $id_mns); 
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '17':	$login = getIMNS($link, $id_mns)."_".translate3(getFIO($link, $_POST["select_user"]));	
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
			case '30': 
						$login =  getIMNS($link, $id_mns).getIP($link, $_POST["select_user"]);
						checkLoginCount($link, $_POST["select_user"], $_POST["select_base"], $login, 0);
						break;
		}
		
		
		$tdata = date("d.m.Y");
		$fild_query = "INSERT INTO `table_fild` (`id`, `id_user`, `id_base`, `id_mns`, `login`,`state`, `request`, `upload` ,`notice`) VALUES (NULL, ";
		$fild_query .= " '".mysqli_real_escape_string($link, $_POST["select_user"])."',";
		$fild_query .= " '".mysqli_real_escape_string($link, $_POST["select_base"])."', ";
		$fild_query .= " '".mysqli_real_escape_string($link, $id_mns)."', '".mysqli_real_escape_string($link, $login)."', '".mysqli_real_escape_string($link, $fild_state[0])."', '".$request_state[0]."',";
		$fild_query .= " '".mysqli_real_escape_string($link, $tdata)."', ";
		$fild_query .= " '".mysqli_real_escape_string($link, $_POST["notice"])."');";
		mysqli_query($link,$fild_query) or die(mysqli_error($link));
		
		
		//echo "<script>window.open(".$_SESSION["HTTP_REFERER"].", '_self');</script>";
		header("Location: ".$_SESSION["HTTP_REFERER"]);
		exit;
		//if ( isset ($_SESSION["edit_table"]) ){
		//	$_SESSION["edit_table"] = null;
		//	echo "<script>history.go(-2);</script>";
		//}else{
		//	//echo "<script>history.go(-2);</script>";
		//	header("Location: main.php");
		//	exit;
		//}
		
	}
	
}

if (isset ($_POST["upd_fild"])){
	
	$state_buf = "";
	if ($_POST['state']==$fild_state[0]){
		$tdata = date("d.m.Y");
		$state_buf = " `upload` = '".mysqli_real_escape_string($link, $tdata)."', ";
	}
	
	$update_query = "UPDATE `table_fild` SET ";
	$update_query .= " `login` = '".mysqli_real_escape_string($link, $_POST['login'])."', ";
	$update_query .= " `date_from` = '".mysqli_real_escape_string($link, $_POST['date_from'])."', ";
	$update_query .= " `date_to` = '".mysqli_real_escape_string($link, $_POST['date_to'])."', ";
	$update_query .= " `state` = '".mysqli_real_escape_string($link, $_POST['state'])."', ";
	$update_query .= " `request` = '".mysqli_real_escape_string($link, $_POST['request'])."', ";
	$update_query .= $state_buf;
	$update_query .= " `notice` = '".mysqli_real_escape_string($link, $_POST['notice'])."' ";
	$update_query .= " WHERE `table_fild`.`id` = ".mysqli_real_escape_string($link, $_POST['id']).";";
	//echo $update_query;
	mysqli_query($link,$update_query) or die(mysqli_error($link));
	
	$mmm=0;
	if ($id_access==0){
		$mmm=0;
	}else{
		$mmm=$id_mns;
	}
	
	$hdata=date("H")+3;
	$ldata=date("d.m.Y ").$hdata.date(":i:s");
	$log_query = "";
	$log_query .= "INSERT INTO `table_log` (id, `id_fild`, `login`, `request`, `state`, `date_from`, `date_to`, `id_mns`, `date_log`) VALUES( null,";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["id"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["login"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["request"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["state"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["date_from"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $_POST["date_to"])."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $id_mns)."', ";
	$log_query .= "'".mysqli_real_escape_string($link, $ldata)."'); ";
	//echo $log_query;
	mysqli_query($link,$log_query) or die(mysqli_error($link));	
	
	header("Location: ".$_SESSION["HTTP_REFERER"]);
	exit;
	//exit;
	//if ( isset ($_SESSION["edit_table"]) ){
	//	$_SESSION["edit_table"] = null;
	//	echo "<script>history.go(-2);</script>";
	//}else{
		//echo "<script>history.go(-2);</script>";
	//	header("Location: main.php");
	//	exit;
	//}
}

if (isset ($_POST["del_fild"])){
	$del_base_query = "DELETE FROM `table_fild` WHERE `table_fild`.`id` = ".mysqli_real_escape_string($link, $_POST['id']);
	mysqli_query($link,$del_base_query) or die(mysqli_error($link));
	
	if ( isset ($_SESSION["edit_table"]) ){
		$_SESSION["edit_table"] = null;
		//echo "<script>history.go(-2);</script>";
		header("Location: ".$_SESSION["HTTP_REFERER"]);
		exit;
	}else{
		//echo "<script>history.go(-2);</script>";
		header("Location: main.php");
		exit;
	}
}

?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Редактирование доступа</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="Учет доступа к информационным ресурсам. Витебская область" />
		<meta name="keywords" content="Учет доступа к информационным ресурсам. Витебская область" />
		<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link rel="stylesheet" href="styles/main.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery1.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		
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
		
	</head>

	<body>
	<div class ="body_div">
		<div class ="center_div">
		<?php 

			if ( (isset ($_POST['select_base'])) or (isset ($_POST['id_fild']))){
				$fild_query = null;
				
				if ( isset ($_POST['id_fild']) ){
					$_SESSION["edit_table"]=0;
					$fild_query = "SELECT * FROM `table_fild` WHERE `id` = ".mysqli_real_escape_string($link, $_POST['id_fild']);
				}else{	
					$fild_query = "SELECT * FROM `table_fild` WHERE `id_user` = ".mysqli_real_escape_string($link, $_POST['select_user'])." and `id_base` = ".mysqli_real_escape_string($link, $_POST['select_base']);
				}
				$result = mysqli_query($link,$fild_query) or die(mysqli_error($link));
				$fild=[];
				for ($fild=[]; $row=mysqli_fetch_assoc($result); $fild[]=$row);
				$disable = "";
				
				foreach($fild as $elem){
					
					#$count_base_query="select count(*) from `table_fild` where `id_base`=".mysqli_real_escape_string($link, $elem['id_base'])." and `id_mns`='".mysqli_real_escape_string($link, $elem['id_mns'])."' and !((`table_fild`.`request`='".$request_state[0]."' and `table_fild`.`state`='".$fild_state[0]."') or (`table_fild`.`request`='".$request_state[0]."' and `table_fild`.`state`='".$fild_state[1]."') or (`table_fild`.`state`='".$fild_state[4]."'))";
					$count_base_query='select count(*) from `table_imns`, `table_fild` where `table_imns`.`id` = `table_fild`.`id_mns` and `table_imns`.`id` = '.$elem['id_mns'].' and `table_fild`.`id_base`="'.$elem["id_base"].'" and !((`table_fild`.`request`="'.$request_state[0].'" and `table_fild`.`state`="'.$fild_state[0].'") or (`table_fild`.`request`="'.$request_state[0].'" and `table_fild`.`state`="'.$fild_state[1].'") or (`table_fild`.`state`="'.$fild_state[4].'") or (`table_fild`.`state`="'.$fild_state[3].'"))';
					$result2 = mysqli_query($link,$count_base_query) or die(mysqli_error($link));
					$fild2=[];
					for ($fild2=[]; $row=mysqli_fetch_assoc($result2); $fild2[]=$row);
					foreach($fild2 as $elem2){
						echo "<H3>Количество действующих учеток в имнс:".$elem2['count(*)']."</H3>";
					}
					
					$user_base_query="select `table_user`.`fio`, `table_base`.`shot_name` from `table_user`, `table_base`, `table_fild` where `table_base`.`id`=`table_fild`.`id_base` and `table_user`.`id`=`table_fild`.`id_user` and `table_fild`.`id`=".$elem["id"];
					$result1 = mysqli_query($link,$user_base_query) or die(mysqli_error($link));
					$fild1=[];
					for ($fild1=[]; $row=mysqli_fetch_assoc($result1); $fild1[]=$row);
					foreach($fild1 as $elem1){
							echo "<H2>".$elem1['shot_name']."</H2>";
							echo "<H2>".$elem1['fio']."</H2>"; 
					}					
					
					echo '<form class="user_update_form" onsubmit="return validate_form();" autocomplete="off" method="post" action="update_fild.php">';
					echo "<p><label><h8>Логин:</h8></label><input type='text' placeholder='Логин' title='".$fild_title['login']."' name='login' value='".$elem['login']."'></p>";
					echo "<p><label><h8>Дата предоставления доступа:</h8></label><input type='text' readonly placeholder='Дата предоставления доступа' title='".$fild_title['date_from']."' onclick='choose_data(this);' id='date_from' name='date_from' value='".$elem['date_from']."'></p>";
					echo "<p><label><h8>Дата прекращения (окончания) доступа:</h8></label><input type='text' readonly placeholder='Дата прекращения (окончания) доступа' title='".$fild_title['date_to']."' onclick='choose_data_to(this);' id='date_to' name='date_to' value='".$elem['date_to']."'></p>";
					//echo "<p><input type='text' placeholder='Продлен' title='Продлен' onclick='choose_data(this);' name='date_next' value='".$elem['date_next']."'></p>";
					$table_select = "<p><label><h8>Статус:</h8></label><select onchange='check_select(this.value)' title ='".$fild_title['state']."' id='state' name='state'>";
					foreach($fild_state as $buf){
						$str = "";
						if ( $buf == $elem["state"]) $str='selected="selected"';
						if (($id_access!=0) && ($buf==$fild_state[1])){
							
						}else{
							$table_select .= "<option value='".$buf."' ".$str.">".$buf."</option>";
						}
					}
					$table_select .= "</select></p>";
					echo $table_select;
					$table_request = "<p><select id='request' name='request' title='".$fild_title['request']."' style='display:none;'>";
					foreach($request_state as $buf){
						$str = "";
						if ( $buf == $elem["request"]) 
							$str='selected="selected"';
						switch ($buf){
							case "продление":
								if ( ($elem["id_base"]==6) or ($elem["id_base"]==7) or ($elem["id_base"]==9) or ($elem["id_base"]==10) or ($elem["id_base"]==11) or ($elem["id_base"]==12) or ($elem["id_base"]==13) or ($elem["id_base"]==14) or ($elem["id_base"]==15) or ($elem["id_base"]==16) or ($elem["id_base"]==17) or ($elem["id_base"]==24) or ($elem["id_base"]==32) or ($elem["id_base"]==34))
									$disable = "disabled";
								break;
							case "разблокировка":
								if ( ($elem["id_base"]==14) or ($elem["id_base"]==18) or ($elem["id_base"]==19) or ($elem["id_base"]==20) or ($elem["id_base"]==24) or ($elem["id_base"]==32) or ($elem["id_base"]==34))
									$disable = "disabled";
								break;
							case "изменить реквизиты":
								if ( ($elem["id_base"]==6) or ($elem["id_base"]==7) or ($elem["id_base"]==8) or ($elem["id_base"]==9) or ($elem["id_base"]==10) or ($elem["id_base"]==12) or ($elem["id_base"]==18) or ($elem["id_base"]==19) or ($elem["id_base"]==20) or ($elem["id_base"]==24) or ($elem["id_base"]==34))
									$disable = "disabled";
								break;
						}
						$table_request .= "<option ".$disable." value='".$buf."' ".$str.">".$buf."</option>";
						$disable = "";
					}					
					$table_request .= "</select></p>";
					echo $table_request;
					echo "<label><h8>Примечание:</h8></label><textarea placeholder='Примечание' title='".$fild_title['notice']."' name='notice'>".$elem["notice"]."</textarea>";
					echo "<input type='text' name='id' style='display:none' value='".$elem['id']."'>";
					echo '<hr><p><H8 style="color:brown">!!! Обратите внимание !!!</H8><h7><br>1) После получения ответов из МНС, необходимо внести данные в соответствующие поля.<br>2) При смене должности, в «Редактировании пользователя» указываете новую должность, сохраняете. Затем, в тех базах, где это предусмотрено и был доступ, необходимо проставить статус "выгружен на область, изменить реквизиты", а в поле «Примечание» проставить "дату изменения должности" и сделать запись: "смена должности". После получения ответа из МНС, сделать статус "действующий", поле «Примечание» очистить от соответствующей записи.<br>3) Если в сведениях из МНС не указана дата предоставления доступа, следует указать дату фактического получения письма.<br>4) Датой прекращения доступа для баз СККС, СКТА, СККО, БЭСТ считается дата получения статуса "отправлено в МНС", для остальных баз - дата получения ответа из МНС.<br>5) Для баз АС ГАИ-Центр, ЕГБДП, АС Паспорт - в поле "Дата прекращения (окончания) доступа" вносится дата, указанная в самой базе, для своевременного продления.</h7><br><br></p>';
					echo '<p><input type="submit" class="button" name="upd_fild" value="Сохранить изменения" title="Сохранить изменения"></p>';
					if ($id_access == 0 ){
						echo '<p><input type="submit" class="button" name="del_fild" value="Удалить" title="Удаление заявки с БД"/></p>';
					}
					echo '</form>';
					
				}
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
				dateFormat: "dd.mm.yy",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
			});	
			$(elem).datepicker("show");
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
					},1)
				},
				changeYear: true,
				dateFormat: "dd.mm.yy",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
			});	
			$(elem).datepicker("show");
		}
	</script>
</html>	