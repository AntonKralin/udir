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

$base_query = "select * from table_base";
$result = mysqli_query($link,$base_query) or die(mysqli_error($link));
$tabs_base=[];
for ($tabs_base=[]; $row=mysqli_fetch_assoc($result); $tabs_base[]=$row);


?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Отчет по количеству</title>
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
							<?php
								echo "<td> </td>";
								foreach($tabs_base as $elem){
										echo "<td>".$elem["shot_name"]."</td>";
								}
							?>
						</tr>
					</thead>
					<tbody class="table_body">	
						
						<?php 
						
							$mns_query = 'select * from `table_imns`';
							$result = mysqli_query($link,$mns_query) or die(mysqli_error($link));
							$imns=[];
							for ($imns=[]; $row=mysqli_fetch_assoc($result); $imns[]=$row);
							foreach ($imns as $eimns){
								echo "<tr>";
								echo "<td>".$eimns['number']."</td>";
								foreach($tabs_base as $elem){
									$work_query = 'select `table_imns`.`number`, count(*) from `table_imns`, `table_fild` where `table_imns`.`id` = `table_fild`.`id_mns` and `table_imns`.`id` = '.$eimns["id"].' and `table_fild`.`id_base`="'.$elem["id"].'" and !((`table_fild`.`request`="'.$request_state[0].'" and `table_fild`.`state`="'.$fild_state[0].'") or (`table_fild`.`request`="'.$request_state[0].'" and `table_fild`.`state`="'.$fild_state[1].'") or (`table_fild`.`state`="'.$fild_state[4].'") or (`table_fild`.`state`="'.$fild_state[3].'"))';
									$result2 = mysqli_query($link,$work_query) or die(mysqli_error($link));
									$count=[];
									for ($count=[]; $row=mysqli_fetch_assoc($result2); $count[]=$row);
										foreach ($count as $ecount){
											echo "<td>".$ecount['count(*)']."</td>";
										}
								}
								echo "</tr>";
							}
						?>
						
					</tbody>
					</table>
				</div>
			
	</body>
	
	<script type="text/javascript" src="js/sort.js"></script>
	
</html>	