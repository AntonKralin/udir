<?php
session_start();
$id_access = null;
$id_mns = $id_access;
include 'connect.php';
include 'const.php';

if (isset ($_SESSION["id_access"])){
	$id_access = $_SESSION["id_access"];
}else{
	exit;
}

$base_query = "select * from table_base";
$result = mysqli_query($link,$base_query) or die(mysqli_error($link));
$tabs_base=[];
for ($tabs_base=[]; $row=mysqli_fetch_assoc($result); $tabs_base[]=$row);

if (isset ($_POST["imns_id"])){
	$id_mns = $_POST["select_menu"];
	$_SESSION["id_mns"] = $id_mns;
	//echo "a".$id_mns;
}

if (isset ($_SESSION["id_mns"])){
	$id_mns = $_SESSION["id_mns"];
}

$BD_submit = null;
if (isset($_GET['BD'])){
	$_SESSION["sBD"] = $_GET['BD'];
}

if (isset ($_SESSION["sBD"])){
	$BD_submit = $_SESSION["sBD"];
} 



?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Корректировка пользователей</title>
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
	</head>

	<body>
					
				<div id="work_all">
					<table border="1" width="100%" cellpadding="0" cols="10" class="sortable" >

					<tbody class="table_body">	
						
						<?php 
							if ( (isset($_POST['report'])) or (isset($_POST['sub_create']))){
								
								if ( isset($_POST['sub_create']) ){
									$all_mns = "";
									if ($id_mns !=0)
										$all_mns = " and `table_fild`.`id_mns` = ".mysqli_real_escape_string($link, $id_mns);
									$base_id = "";
									foreach($tabs_base as $elem){
										if ($elem["shot_name"] == $BD_submit)
											$base_id = $elem["id"];
									}
									$view_query="select * from `table_fild`, `table_user`, `table_imns` where `table_fild`.`id_base`='".$base_id."' ".$all_mns." and `table_fild`.`state`='".$fild_state[0]."' and `table_fild`.`id_mns`=`table_imns`.`id` and `table_fild`.`id_user`=`table_user`.`id` ORDER BY `table_fild`.`request`";
									$result = mysqli_query($link,$view_query) or die(mysqli_error($link));
									$table=[];
									for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
									$table_name="";
									$table_name_befo="";
									$first =0;
									foreach ($table as $elem){
										if ($table_name!=$elem['request']) {
											$table_name=$elem['request'];
										}
										//if ($table_name != $table_name_befo){ 
											//echo '<tr><td align="center" colspan="10">'.$elem['request'].'</td></tr>';
										//}
										
										switch($BD_submit){
											case "АСУ «ГАИ-Центр»":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															//echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">IP-адрес</td></tr>';
															echo '<tr><td>Налоговый орган</td><td colspan="9">Должность<br>ФИО</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														//$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="9">'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														//$view_table .='<td colspan="8">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Продлить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать,  продлить доступ и установить пароль по умолчанию:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Изменить реквизиты к информационному ресурсу:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											case "АИС Паспорт":
												switch($elem['request']){
													case $request_state[0]: 
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес пользователя</td></tr>';
														}
														$view_table = '<tr>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Продлить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать и назначить новый пароль:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Изменить сведения и продлить срок действия:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											case "ЕГБДП":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].', УНП '.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Продлить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать и назначить новый пароль:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения (должность, телефон):</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>Наименование инспекции</td><td>ФИО сотрудника</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
											case "ФСЗН":
												if ($first==0){
													echo '<tr><td>Наименование инспекции</td><td>ФИО сотрудника</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Разблокировать и установить новый пароль:</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "ОАЦ":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Должность<br>телефон</td><td colspan="7">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['telefon'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения о занимаемой должности:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
											case "АСПК Беркут":
												if ($first==0){
													echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Продлить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Разблокировать:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Внести изменения:</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
											case "БЭСТ":
												if ($first==0){
													echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Разблокировать и назначить новый пароль:</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "Велком":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Установить пароль по умолчанию:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "МТС":
												switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Установить пароль по умолчанию</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Внести изменения в должность и фамилию:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>ФИО должностного лица</td><td>Код, наименование инспекции</td><td>Должность<br>контактный телефон</td><td colspan="7">Имя пользователя</td></tr>';
											case "СПИН Белтелеком":
												if($first==0){
													echo '<tr><td>ФИО должностного лица</td><td>Код, наименование инспекции</td><td>Должность<br>контактный телефон</td><td colspan="7">Имя пользователя</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Доступ предоставить:</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['telefon'].'</td>';
														$view_table .='<td colspan="7">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Доступ прекратить:</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['telefon'].'</td>';
														$view_table .='<td colspan="7">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать и установить пароль по умолчанию:</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['telefon'].'</td>';
														$view_table .='<td colspan="7">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														break;	

												}
											break;
											case "ЕГРНИ":
												switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Логин пользователя</td><td>ФИО лица, получившего доступ</td><td>Должность лица, получившего доступ</td><td>Полное наименование организации (подразделения)</td><td>УНП организации</td><td>Место нахождения организации</td><td colspan="4">Законодательные акты, в соответствии с которыми лицо имеет право получать информацию на безвозмездной основе</td></tr>';
														}
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['address'].'</td>';
														$view_table .= '<td colspan="4">п.п.1.12 п.1 ст.107 Налогового кодекса Республики Беларусь (Общая часть)</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Логин пользователя</td><td>ФИО лица, получившего доступ</td><td>Должность лица, получившего доступ</td><td>Полное нименование организации</td><td>УНП организации</td><td colspan="5">Место нахождения организации</td></tr>';
														}
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td colspan="5">'.$elem['address'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Разблокировать доступ и назначить новый пароль:</td></tr>';
															echo '<tr><td>Логин пользователя</td><td>ФИО лица, получившего доступ</td><td>Должность лица, получившего доступ</td><td>Полное наименование организации (подразделения)</td><td>УНП организации</td><td colspan="5">Место нахождения организации</td></tr>';
														}
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td colspan="5">'.$elem['address'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения (о занимаемой должности, фамилии):</td></tr>';
															echo '<tr><td>Логин пользователя</td><td>ФИО лица, получившего доступ</td><td>Должность лица, получившего доступ</td><td>Полное нименование организации</td><td>УНП организации</td><td colspan="5">Место нахождения организации</td></tr>';
														}
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td colspan="5">'.$elem['address'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
											case "Реестр цен":
												switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 															
															echo '<tr><td align="center" colspan="19">Предоставить доступ:</td></tr>';
															echo '<tr><td>Страна</td><td>УНП организации</td><td>Сокращенное наименование организации</td><td>Полное наименование организации</td><td>Адрес</td><td>Почтовый адрес</td><td>Контактный номер телефона организации</td><td>Электронная почта организации</td><td>Сфера деятельности организации</td><td>БИК</td><td>Расчетный счет</td><td>Валюта счета</td><td>Наименование банка</td><td>Адрес банка</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Контактный номер телефона</td><td>Электронная почта представителя</td></tr>';
														}
														$fio = explode(" ", $elem['fio']);
														if (!isset($fio[3])){
															$fio[3]='';
														}
														$view_table .='<td>Республика Беларусь</td>';
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['post'].', '.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td>Государственный сектор</td>';
														$view_table .='<td>'.$elem['bik'].'</td>';
														$view_table .='<td>'.$elem['score'].'</td>';
														$view_table .='<td>BYN</td>';
														$view_table .='<td>'.$elem['bank'].'</td>';
														$view_table .='<td>'.$elem['bank_address'].'</td>';
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
																echo '<tr><td>Страна</td><td>УНП организации</td><td>Сокращенное наименование организации</td><td>Полное наименование организации</td><td>Адрес</td><td>Почтовый адрес</td><td>Контактный номер телефона организации</td><td>Электронная почта организации</td><td>Сфера деятельности организации</td><td>БИК</td><td>Расчетный счет</td><td>Валюта счета</td><td>Наименование банка</td><td>Адрес банка</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Контактный номер телефона</td><td>Электронная почта представителя</td></tr>';
														}
														$fio = explode(" ", $elem['fio']);
														if (!isset($fio[3])){
															$fio[3]='';
														}
														$view_table .='<td>Республика Беларусь</td>';
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['post'].', '.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td>Государственный сектор</td>';
														$view_table .='<td>'.$elem['bik'].'</td>';
														$view_table .='<td>'.$elem['score'].'</td>';
														$view_table .='<td>BYN</td>';
														$view_table .='<td>'.$elem['bank'].'</td>';
														$view_table .='<td>'.$elem['bank_address'].'</td>';
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения (о занимаемой должности, фамилии):</td></tr>';
																echo '<tr><td>Страна</td><td>УНП организации</td><td>Сокращенное наименование организации</td><td>Полное наименование организации</td><td>Адрес</td><td>Почтовый адрес</td><td>Контактный номер телефона организации</td><td>Электронная почта организации</td><td>Сфера деятельности организации</td><td>БИК</td><td>Расчетный счет</td><td>Валюта счета</td><td>Наименование банка</td><td>Адрес банка</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Контактный номер телефона</td><td>Электронная почта представителя</td></tr>';
														}
														$fio = explode(" ", $elem['fio']);
														if (!isset($fio[3])){
															$fio[3]='';
														}
														$view_table .='<td>Республика Беларусь</td>';
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['post'].', '.$elem['address'].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td>Государственный сектор</td>';
														$view_table .='<td>'.$elem['bik'].'</td>';
														$view_table .='<td>'.$elem['score'].'</td>';
														$view_table .='<td>BYN</td>';
														$view_table .='<td>'.$elem['bank'].'</td>';
														$view_table .='<td>'.$elem['bank_address'].'</td>';
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														echo $view_table;											
														break;	
														
												}
											break;
											case "КНД":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Установить новый пароль и продлить срок действия:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения о занимаемой должности, фамилии:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											case "РН Республика":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать учетную запись и установить новый пароль:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения о занимаемой должности, фамилии:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											case "БИ АИС РН":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Разблокировать учетную запись и установить новый пароль:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения о занимаемой должности, фамилии:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>Имя пользователя</td><td>IP-адрес</td><td colspan="6">Новые учетные данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="6">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											//заголовок вывести один раз в начале
											//echo '<tr><td>ФИО должностного лица</td><td>Наименование инспекции, структурного подразделения</td><td>Должность, контактный телефон</td><td colspan="7">Уровень доступа</td></tr>';
											case "СККС":
												if ($first==0){
													echo '<tr><td>ФИО должностного лица</td><td>Наименование инспекции, структурного подразделения</td><td>Должность, контактный телефон</td><td colspan="7">Уровень доступа</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Доступ предоставить</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Доступ прекратить</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "СККО":
												if ($first==0){
													echo '<tr><td>ФИО должностного лица</td><td>Личный № паспорта</td><td>Наименование инспекции, структурного подразделения</td><td>Должность, контактный телефон</td><td colspan="7">Уровень доступа</td><td>Сертификат</td></tr>';
												}
												switch($elem['request']){
													case ( $request_state[0] or $request_state[2] ): 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="12">Доступ предоставить</td></tr>';
														}	
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .= '<td></td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .='<td>'.$elem['notice'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="12">Доступ прекратить</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .= '<td></td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .='<td>'.$elem['notice'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "СКТА":
												if ($first==0){
													echo '<tr><td>ФИО должностного лица</td><td>Личный № паспорта</td><td>Наименование инспекции, структурного подразделения</td><td>Должность, контактный телефон</td><td colspan="7">Уровень доступа</td><td>Сертификат</td></tr>';
												}
												switch($elem['request']){
													case $request_state[0] or $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="12">Доступ предоставить</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .= '<td></td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .='<td>'.$elem['notice'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="12">Доступ прекратить</td></tr>';
														}	
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .= '<td></td>';
														$view_table .='<td>'.$elem['name'].', '.$elem['unit'].'</td>';
														$view_table .='<td>'.$elem['job'].', '.$elem['telefon'].'</td>';
														//$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														if ($elem["id_mns"]==1){
															$view_table .='<td colspan="7">область</td>';
														}else{
															$view_table .='<td colspan="7">район</td>';
														}
														$view_table .='<td>'.$elem['notice'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											case "ЦХД ТИ":
												if ($first==0){
													echo '<tr><td>№ п/п</td><td>Фамилия, собственное имя, отчество (если такое имеется)</td><td>Идентификатор пользователя</td><td>Должность</td><td>Наименование инспекции МНС (управления (отдела) по работе с плательщиками)</td><td>Признак доступа</td></tr>';
												}
												$view_table = '<tr>';
												$view_table .= '<td></td>';
												$view_table .='<td>'.$elem['fio'].'</td>';
												$view_table .= '<td>'.$elem['login'].'</td>';
												$view_table .='<td>'.$elem['job'].'</td>';
												$view_table .='<td>'.$elem['name'].'</td>';
												switch($elem['request']){
													case $request_state[0]: 
														$view_table .='<td>предоставление</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table .='<td>прекращение</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table .='<td>продление</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]: 
														$view_table .='<td>разблокировка</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[4]: 
														$view_table .='<td>изменить реквизиты</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;											
											case "Портал МНС":
											switch($elem['request']){
													case $request_state[0]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td>IP-адрес</td><td colspan="7">Приказ</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td colspan="7">'.$elem['notice'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]:
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность<br>ФИО</td><td colspan="8">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].'<br>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											
											
											case "АИС ГИМ":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность* ФИО</td><td>Имя пользователя</td><td colspan="7">IP-адрес</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].', '.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['job'].' '.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['ip'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекратить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Продлить доступ:</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>Должность* ФИО</td><td colspan="8">Имя пользователя</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['job'].' '.$elem['fio'].'</td>';
														$view_table .='<td colspan="8">'.$elem['login'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){ 
															echo '<tr><td align="center" colspan="10">Изменить сведения (должность, телефон):</td></tr>';
															echo '<tr><td>Налоговый орган</td><td>ФИО</td><td>Имя пользователя</td><td colspan="7">Новые данные</td></tr>';
														}	
														$view_table .='<td>'.$elem['number'].', '.$elem['name'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td>'.$elem['login'].'</td>';
														$view_table .='<td colspan="7">'.$elem['job'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
											
											
											case "АИС ГАИ":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="11">Предоставить доступ:</td></tr>';
															echo '<tr><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Дата рождения (ДД.ММ.ГГГГ)</td><td>Идентификационный номер</td><td>Номер служебного телефона</td><td>IP-адрес</td><td>E-mail</td><td>Наименование государственного органа, организации</td><td>Структурное подразделение</td><td>Должность</td></tr>';
														}
														$fio = explode(" ", $elem['fio']);
														if (!isset($fio[3])){
															$fio[3]='';
														}
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .='<td></td>';
														$view_table .='<td></td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td>МИНИСТЕРСТВО ПО НАЛОГАМ И СБОРАМ РЕСПУБЛИКИ БЕЛАРУСЬ</td>';														
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="11">Прекратить доступ:</td></tr>';
															echo '<tr><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Дата рождения (ДД.ММ.ГГГГ)</td><td>Идентификационный номер</td><td>Номер служебного телефона</td><td>IP-адрес</td><td>E-mail</td><td>Наименование государственного органа, организации</td><td>Структурное подразделение</td><td>Должность</td></tr>';
														}
														$fio = explode(" ", $elem['fio']);
														if (!isset($fio[3])){
															$fio[3]='';
														}
														$view_table .='<td>'.$fio[0].'</td>';
														$view_table .='<td>'.$fio[1].'</td>';
														$view_table .='<td>'.$fio[2].'</td>';
														$view_table .='<td></td>';
														$view_table .='<td></td>';
														$view_table .='<td>'.$elem['telefon'].'</td>';
														$view_table .='<td>'.$elem['ip'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td>МИНИСТЕРСТВО ПО НАЛОГАМ И СБОРАМ РЕСПУБЛИКИ БЕЛАРУСЬ</td>';														
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														break;	
														
												}
											break;
											
											
											case "ГИР ФСЗН":
												switch($elem['request']){
													case $request_state[0]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Предоставление доступа:</td></tr>';
															echo '<tr><td>Код ИМНС</td><td>Наименование инспекции МНС (отдела, управления по работе с плательщиками)</td><td>УНП ИМНС</td><td>ФИО</td><td>Идентификационный (личный) номер из паспорта</td><td>Должность</td><td>Адрес электронной почты</td><td colspan="2">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td></td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td colspan="2">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[1]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Прекращение доступа:</td></tr>';
															echo '<tr><td>Код ИМНС</td><td>Наименование инспекции МНС (отдела, управления по работе с плательщиками)</td><td>УНП ИМНС</td><td>ФИО</td><td>Идентификационный (личный) номер из паспорта</td><td>Должность</td><td>Адрес электронной почты</td><td colspan="2">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td></td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td colspan="2">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;
													case $request_state[2]: 
														break;
													case $request_state[3]: 
														break;
													case $request_state[4]: 
														$view_table = '<tr>';
														if ($table_name != $table_name_befo){
															echo '<tr><td align="center" colspan="10">Изменение реквизитов (смена должности):</td></tr>';
															echo '<tr><td>Код ИМНС</td><td>Наименование инспекции МНС (отдела, управления по работе с плательщиками)</td><td>УНП ИМНС</td><td>ФИО</td><td>Идентификационный (личный) номер из паспорта</td><td>Должность</td><td>Адрес электронной почты</td><td colspan="2">Контактный телефон</td></tr>';
														}
														$view_table .='<td>'.$elem['number'].'</td>';
														$view_table .='<td>'.$elem['shot_imns'].'</td>';
														$view_table .='<td>'.$elem['unp'].'</td>';
														$view_table .='<td>'.$elem['fio'].'</td>';
														$view_table .='<td></td>';
														$view_table .='<td>'.$elem['job'].'</td>';
														$view_table .='<td>'.$elem['mail'].'</td>';
														$view_table .='<td colspan="2">'.$elem['telefon'].'</td>';
														$view_table .= '</tr>';
														echo $view_table;
														break;	
														
												}
											break;
										}
										
										if ($table_name != $table_name_befo){
											$table_name_befo = $elem['request'];
										}
										$first +=1;
									}
								}
								
								if ( isset($_POST['report']) )
								switch ($_POST['report']){

									case "Сформировать": 
										$all_mns = "";
										$select_request = $_POST["request"];
										if ($id_mns !=0)
											$all_mns = " and `table_fild`.`id_mns` = ".mysqli_real_escape_string($link, $id_mns);
										$base_id = "";
										foreach($tabs_base as $elem){
											if ($elem["shot_name"] == $BD_submit)
												$base_id = $elem["id"];
										}
										$report_query = "select `table_fild`.`id_mns` ";
										$achek = null;
										if (isset ($_POST["req"])){
											$achek = $_POST["req"];
											for ($i=0; $i<count($achek); $i++){
												//if ($i != 0){$report_query .= ", ";}
												$report_query .= ", `".mysqli_real_escape_string($link, $achek[$i])."`";
											}
										}
										if (isset($_POST["reqIMNS"])){
											$report_query .= ", ".mysqli_real_escape_string($link, $_POST["reqIMNS"]);
										}
										
										if (isset($_POST["reqJob"])){
											$report_query .= ", ".mysqli_real_escape_string($link, $_POST["reqJob"]);
										}
										
										if (isset($_POST["reqUnit"])){
											$report_query .= ", ".mysqli_real_escape_string($link, $_POST["reqUnit"]);
										}
										
										$report_query .= " from `table_user`, `table_fild`, `table_imns` where `table_imns`.`id`=`table_fild`.`id_mns` and `table_fild`.`state`='".$fild_state[0]."' and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns."  and `table_fild`.`id_base` = ".mysqli_real_escape_string($link, $base_id)." and `table_fild`.`request`='".mysqli_real_escape_string($link, $select_request)."'";
										$result = mysqli_query($link,$report_query) or die(mysqli_error($link));
										$table=[];
										for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
										//echo $report_query;
										//print_r($table);
										foreach ($table as $elem){
											echo "<tr>";
											 //$answer = "";
											 if (isset($_POST["reqIMNS"])){
												 $answer = $elem["number"].", ".$elem["name"];
												 echo "<td>".$answer."</td>";
											 }
											 if (isset($_POST["reqJob"])){
												 $answer = $elem["job"]." ".$elem["fio"];
												 echo "<td>".$answer."</td>";
											 }
											 if (isset($_POST["reqUnit"])){
												 $answer = $elem["unit"]." ".$elem["job"]." ".$elem["fio"];
												 echo "<td>".$answer."</td>";
											 }
											 if (isset ($_POST["req"])){
												 for ($i=0; $i<count($achek); $i++){
													 //echo $achek[$i];
													 //$buf = $achek[$i];
													 echo "<td>".$elem[$achek[$i]]."</td>";
													 //$answer .= $elem[$achek[$i]]." ";
												 }
											 }
											 echo "</tr>";
										}
										break;
								}
							}	
						?>
						
					</tbody>
					</table>
				</div>
			
	</body>
	
</html>	