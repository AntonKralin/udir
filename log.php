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

if (isset ($_POST["check"])){
	//$d = getdate();
	$tdata = date("d.m.Y");
	$checked = $_POST["check"];
	$n = count($checked);
	for ($i=0; $i<$n; $i++){
		$state_query = "UPDATE `table_fild` SET `state` ='".$fild_state[1]."', `upload`='".$tdata."' WHERE `table_fild`.`id` = ".$checked[$i];
		mysqli_query($link,$state_query) or die(mysqli_error($link));
	}
}

?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Логи</title>
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
		<form id="imns_label" method="post" autocomplete="off" action="unloaded.php">
			<select name="select_menu">
				<?php 
					$mns_buf="";
					if ($id_access == 0 ){
						$mns_buf ="";
						echo "<option value='0'>All</option>";
					}else {
						$mns_buf= "where `id`= ".mysqli_real_escape_string($link, $id_mns);
					}
					$mns_query = 'select * from `table_imns` '.$mns_buf;
					$result = mysqli_query($link,$mns_query) or die(mysqli_error($link));
					$imns=[];
					for ($imns=[]; $row=mysqli_fetch_assoc($result); $imns[]=$row);
					foreach($imns as $elem){
						//echo $elem['name'];
						$select="";
						if ($elem['id']==$id_mns){
							$select='selected="selected"';
						}
						echo "<option ".$select." value='".$elem["id"]."'>".$elem['name']."</option>";
					}
				?>
			</select>
			<?php 
				if ($id_access == 0) {
					echo "<input type='submit' value='применить' name='imns_id'>";
				}
			?>
		</form>
		
		<div id="log_out">
			<H9>«Учет доступа к информационным ресурсам». Логи.</H9>
			<a href="index.php" id="log_out" class="button">ВЫЙТИ</a>
		</div>
			
		
		<div id="tabs">
			<div class="tab"> 
			<form method="GET" autocomplete="off" action="log.php">
				<?php 
					
					foreach($tabs_base as $elem){
						$col="";
							if ($elem["shot_name"]==$BD_submit)
								$col=" style='color:white; background-color:#669999'' ";
							echo "<input type='submit' ".$col." value='".$elem["shot_name"]."' name='BD' title='".$elem["name"]."'>";
					}
				?>
			</form>	
			</div>
			
			<form method="post" autocomplete="off" action="log.php">
				<div id="work">
					<table id="date-table" border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
					
					<thead>
						<tr>
							<td width="13%">ФИО</td>
							<td width="13%">Логин БД</td>
							<td width="13%">Статус</td>
							<td width="4%">Статус корректировки</td>
							<td width="5%">Дата предоставления</td>
							<td width="7%">Дата окончания</td>
							<td width="4%">МНС</td>
							<td >Время изменения</td>					
						</tr>
					</thead>
					<tbody class="table_body">	
						
						<?php 
							if (isset($BD_submit)){
								
								$base_id = "";
								foreach($tabs_base as $elem){
									if ($elem["shot_name"] == $BD_submit)
										$base_id = $elem["id"];
								}
								//echo $base_id;
								//echo $id_mns;
								$all_mns = "";
								if ($id_mns !=0)
									$all_mns = " and `table_fild`.`id_mns` = ".mysqli_real_escape_string($link, $id_mns);

								$table_query = "SELECT `table_user`.`fio`, `table_fild`.`id_user`, `table_fild`.`id`, `table_fild`.`request`, `table_fild`.`id_base`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_log`.`id_mns`, `table_log`.`date_log` from `table_log`, `table_user`, `table_fild`, `table_imns` WHERE `table_log`.`id_fild`=`table_fild`.`id` and `table_imns`.`id`=`table_fild`.`id_mns` and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns."  and `table_fild`.`id_base` = ".mysqli_real_escape_string($link, $base_id);
								$result = mysqli_query($link,$table_query) or die(mysqli_error($link));
								$table=[];
								for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
									foreach($table as $elem){
										echo "<tr>"; 
										$col="";
										$col2="";
										echo "<td ".$col.">".$elem["fio"]."</td>";
										echo "<td".$col2.">".$elem["login"]."</td>";
										echo "<td".$col2.">".$elem["state"]."</td>";
										echo "<td".$col2.">".$elem["request"]."</td>";										
										echo "<td".$col2.">".$elem["date_from"]."</td>";
										echo "<td".$col2.">".$elem["date_to"]."</td>";
										$who="";
										switch ($elem["id_mns"]){
											case 0: $who=$num_imns[0]; break;
											case 1: $who=$num_imns[1]; break;
											case 2: $who=$num_imns[2]; break;
											case 3: $who=$num_imns[3]; break;
											case 4: $who=$num_imns[4]; break;
											case 5: $who=$num_imns[5]; break;
											case 6: $who=$num_imns[6]; break;
											case 7: $who=$num_imns[7]; break;
											case 8: $who=$num_imns[8]; break;
											case 9: $who=$num_imns[9]; break;
											case 10: $who=$num_imns[10]; break;
											case 11: $who=$num_imns[11]; break;
										}
										echo "<td".$col2.">".$who."</td>";
										echo "<td".$col2.">".$elem["date_log"]."</td>";
										echo "</tr>";
									}
							}	
						?>
						
					</tbody>
					</table>
				</div>
			</form>
	</body>
	
	<script type="text/javascript" src="js/sort.js"></script>
	
	<script>
		var dataTable = document.getElementById("date-table");
		var checkItAll= dataTable.querySelector("input[name='check_all']");
		var inputs = dataTable.querySelectorAll('tbody>tr>td>input');
		
		checkItAll.addEventListener('change', function(){
			if (checkItAll.checked){
				//inputs.forEach(function(input){
				//	input.checked = true;
				//})
				Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=true;})
				
			}else{
				//inputs.forEach(function(input){
				//	input.checked = false;
				//})
				Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=false;})
			}
		});
		
		$( "#check" ).dialog({
			autoOpen: false,
			width: 500
		})
	</script>
</html>	