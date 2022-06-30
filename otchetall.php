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
		<title>«Учет доступа к информационным ресурсам» | Отчет по доступу</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="«Учет доступа к информационным ресурсам»" />
		<meta name="keywords" content="«Учет доступа к информационным ресурсам»" />
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
							<?php 
								if ($id_access == 0 ){
								echo '<td width="13%">Инспекция МНС</td>';
								}
							?>
							<td width="13%">Ф.И.О</td>
							<td width="13%">Должность</td>
							<td width="13%">Подразделение</td>
							<td width="5%">Имя пользователя ИР</td>
							<td width="5%">Состояние</td>
							<td width="5%">Дата предоставления доступа </td>
							<td width="5%">Дата отключения доступа </td>
							<td width="5%">IP-адрес</td>
							<td width="5%">ИР</td>
						</tr>
					</thead>
					<tbody class="table_body">	
						<!-- <a class="print-doc" href="javascript:(print());">Печать</a> -->
						<?php 
							if (isset ($_POST["req"])){
							
								$achek = $_POST["req"];
								for ($i=0; $i<count($achek); $i++){
									
									$base=" and `table_fild`.`id_base`=".$achek[$i];
									
									$all_mns="";
									if ($id_mns != 0){
										$all_mns = " and `table_imns`.`id` = ".mysqli_real_escape_string($link, $id_mns)." ";
									}
									$otchet_query = "select `table_user`.`fio`, `table_user`.`unit`, `table_user`.`ip`, `table_base`.`shot_name`,  `table_user`.`job`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_imns`.`number`, `table_imns`.`name` from `table_user`, `table_imns`, `table_fild`, `table_base` where (`table_fild`.`id_user` = `table_user`.`id` and `table_fild`.`id_mns` = `table_imns`.`id` ".$all_mns." and `table_fild`.`id_base` = `table_base`.`id` ".$base."  and `table_fild`.`state`!='".$fild_state[4]."' ) and !((`table_fild`.`request`='".$request_state[0]."' and `table_fild`.`state`='".$fild_state[0]."') or (`table_fild`.`request`='".$request_state[0]."' and `table_fild`.`state`='".$fild_state[1]."') or (`table_fild`.`state`='".$fild_state[4]."'))";
									$result = mysqli_query($link,$otchet_query) or die(mysqli_error($link));
									$table=[];
									for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
									foreach($table as $elem){
										echo "<tr>";
										echo "<td>".$elem["number"]."</td>";
										if ($id_access == 0 ){
										echo "<td>".$elem["name"]."</td>";;
										}
										echo "<td>".$elem["fio"]."</td>";
										echo "<td>".$elem["job"]."</td>";
										// Подразделение:
										echo "<td>".$elem["unit"]."</td>";
										echo "<td>".$elem["login"]."</td>";
										//echo "<td>".$elem["state"]."</td>";
										if ($elem["state"] == $fild_state[3]){
											echo "<td>".$elem["state"]."</td>";
											echo "<td>".$elem["date_from"]."</td>";
											echo "<td>".$elem["date_to"]."</td>";
										}else {
											echo "<td>".$fild_state[2]."</td>";
											echo "<td>".$elem["date_from"]."</td>";
											
											echo "<td></td>";
										}
										
										
										
										echo "<td>".$elem["ip"]."</td>";
										echo "<td>".$elem["shot_name"]."</td>";
										echo "</tr>";
									}
								}	
							}	
						?>
						
					</tbody>
					</table>
				</div>
			
	</body>
	
	<script type="text/javascript" src="js/sort.js"></script>
	
</html>	