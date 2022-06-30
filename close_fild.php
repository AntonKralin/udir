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
		<title>«Учет доступа к информационным ресурсам» | Прекращенный доступ</title>
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
					<table border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
					<thead>
						<tr>
						<td width="3%" >Код ИМНС</td>
						<td width="13%" >ФИО</td>
						<td width="13%">Подразделение</td>
						<td width="13%">Должность</td>
						<td width="3%">Телефон</td>
						<td width="3%">IP</td>
						<td width="3%">Логин БД</td>
						<td width="3%">Дата предоставления</td>
						<td width="3%">Дата прекращения</td>
						<td width="3%">Дата выгрузки</td>
						<td width="3%">Статус</td>
						<td >Примечание</td>
						<td width="5%">ИР</td>
					</tr>
					</thead>
					<tbody class="table_body">	
						<!-- <a class="print-doc" href="javascript:(print());">Печать</a> -->
					<?php 
							$all_mns="";
							if ($id_mns != 0){
								$all_mns = " and `table_imns`.`id` = ".mysqli_real_escape_string($link, $id_mns)." ";
							}

							$table_query = "SELECT `table_user`.`fio`, `table_user`.`ip`, `table_base`.`shot_name`, `table_user`.`comp_name`, `table_user`.`ad_login`, `table_imns`.`number`, `table_user`.`telefon`, `table_user`.`unit`, `table_user`.`job`, `table_user`.`del`, `table_fild`.`id_user`, `table_fild`.`id`, `table_fild`.`request`, `table_fild`.`id_base`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_fild`.`date_next`, `table_fild`.`upload`, `table_fild`.`notice` from `table_user`, `table_fild`, `table_base`, `table_imns` WHERE (`table_fild`.`state`='".$fild_state[4]."' or `table_fild`.`state`='".$fild_state[3]."') and `table_fild`.`id_base` = `table_base`.`id` and `table_imns`.`id`=`table_fild`.`id_mns`  and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns;
							$result = mysqli_query($link,$table_query) or die(mysqli_error($link));
							$table=[];
							for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
								foreach($table as $elem){
									//echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
									echo "<tr>"; 
									$col="";
									$col2="";
									if ($elem["del"]==0){
										$col = " style='color:brown;' ";
									}
									if ($elem["state"]==$fild_state[3]){
										//$col2= " style='background-color: blue;' ";
										$col2= " style='color:brown;' ";
									}
									echo "<td>".$elem["number"]."</td>";
									echo "<td ".$col.">".$elem["fio"]."</td>";
									echo "<td".$col.">".$elem["unit"]."</td>";
									echo "<td".$col.">".$elem["job"]."</td>";
									echo "<td".$col.">".$elem["telefon"]."</td>";
									echo "<td".$col.">".$elem["ip"]."</td>";
									//echo "<td".$col.">".$elem["comp_name"]."</td>";
									//echo "<td".$col.">".$elem["ad_login"]."</td>";
									echo "<td".$col2.">".$elem["login"]."</td>";
									echo "<td".$col2.">".$elem["date_from"]."</td>";
									echo "<td".$col2.">".$elem["date_to"]."</td>";
									echo "<td".$col2.">".$elem["upload"]."</td>";
									//echo "<td".$col2.">".$elem["date_next"]."</td>";
									//echo "<td>".$elem["state"]."</td>";
									if (($elem["state"]==$fild_state[0]) or ($elem["state"]==$fild_state[1])){
										echo "<td".$col2.">".$elem["state"].", ".$elem["request"]."</td>";
									}else{
										echo "<td".$col2.">".$elem["state"]."</td>";
									}
									echo "<td".$col2.">".$elem["notice"]."</td>";
									echo "<td>".$elem["shot_name"]."</td>";
									echo "</tr>";
								}
							
					?>
						
					</tbody>
					</table>
				</div>
			
	</body>
	
	<script type="text/javascript" src="js/sort.js"></script>
	
</html>	