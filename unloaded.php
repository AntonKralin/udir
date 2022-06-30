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

$_SESSION["HTTP_REFERER"] = 'unloaded.php';

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

if (isset($_POST['report'])){
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
			$report_query = "select `table_fild`.`id_mns`, ";
			$achek = null;
			if (isset ($_POST["req"])){
				$achek = $_POST["req"];
				for ($i=0; $i<count($achek); $i++){
					if ($i != 0){$report_query .= ", ";}
					$report_query .= "`".mysqli_real_escape_string($link, $achek[$i])."`";
				}
			}
			if (isset($_POST["reqIMNS"])){
				$report_query .= mysqli_real_escape_string($link, $_POST["reqIMNS"]);
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
			foreach ($table as $elem){
				 $answer = "";
				 if (isset($_POST["reqIMNS"])){
					 $answer .= $elem["number"].", ".$elem["name"]." ";
				 }
				 if (isset($_POST["reqJob"])){
					 $answer .= $elem["job"]." ".$elem["fio"]." ";
				 }
				 if (isset($_POST["reqUnit"])){
					 $answer .= $elem["unit"]." ".$elem["job"]." ".$elem["fio"]." ";
				 }
				 if (isset ($_POST["req"])){
					 for ($i=0; $i<count($achek); $i++){
						 //echo $achek[$i];
						 //$buf = $achek[$i];
						 //echo $elem[$achek[$i]];
						 $answer .= $elem[$achek[$i]]." ";
					 }
				 }
				 echo $answer."<BR>";
			}
			break;
	}
}
?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Выгруженные на область</title>
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
			<H9>«Учет доступа к информационным ресурсам». Выгруженные на область.</H9>
			<a href="index.php" id="log_out" class="button">ВЫЙТИ</a>
		</div>
			
		
		<div id="tabs">
			<div class="tab"> 
			<form method="GET" autocomplete="off" action="unloaded.php">
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
			
			<form method="post" autocomplete="off" action="unloaded.php">
				<div id="work">
					<table id="date-table" border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
					
					<thead>
						<tr>
							<td width="2%">ИМНС</td>
							<td width="13%">ФИО</td>
							<td width="13%">Подразделение</td>
							<td width="13%">Должность</td>
							<td width="4%">Телефон</td>
							<td width="5%">IP</td>
							<!-- <td width="8%">Имя компьютера</td> -->
							<!-- <td width="7%">Логин AD</td> -->
							<td width="7%">Логин БД</td>
							<td width="5%">Статус корректировки</td>
							<td >Примечание</td>
							<?php 
								if($id_access == 0){
									echo "<td name='undefined' width='3%'><input type='checkbox' name='check_all' />Выгрузка</td>";
								}
							?>						
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

								$table_query = "SELECT `table_user`.`fio`, `table_user`.`ip`, `table_user`.`comp_name`, `table_user`.`ad_login`, `table_user`.`telefon`, `table_user`.`unit`, `table_user`.`job`, `table_user`.`del`, `table_fild`.`id_user`, `table_fild`.`id`, `table_fild`.`request`, `table_fild`.`id_base`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_fild`.`date_next`, `table_fild`.`upload`, `table_fild`.`notice`, `table_imns`.`number`, `table_imns`.`name` from `table_user`, `table_fild`, `table_imns` WHERE `table_imns`.`id`=`table_fild`.`id_mns` and `table_fild`.`state`='".$fild_state[0]."' and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns."  and `table_fild`.`id_base` = ".mysqli_real_escape_string($link, $base_id);
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
										echo "<td".$col2.">".$elem["number"]."</td>";
										echo "<td ".$col.">".$elem["fio"]."</td>";
										echo "<td".$col.">".$elem["unit"]."</td>";
										echo "<td".$col.">".$elem["job"]."</td>";
										echo "<td".$col.">".$elem["telefon"]."</td>";
										echo "<td".$col.">".$elem["ip"]."</td>";
										//echo "<td".$col.">".$elem["comp_name"]."</td>";
										//echo "<td".$col.">".$elem["ad_login"]."</td>";
										echo "<td".$col2.">".$elem["login"]."</td>";
										//echo "<td".$col2.">".$elem["date_from"]."</td>";
										//echo "<td".$col2.">".$elem["date_to"]."</td>";
										//echo "<td".$col2.">".$elem["upload"]."</td>";
										//echo "<td".$col2.">".$elem["date_next"]."</td>";
										//echo "<td>".$elem["state"]."</td>";
										echo "<td".$col2.">".$elem["request"]."</td>";
										echo "<td".$col2.">".$elem["notice"]."</td>";
										if ($id_access==0){
											$form = '<input class="button" type="button" onclick="edit_fild('.$elem["id"].')" value="✎" title="Изменить доступ">';
											echo "<td><input type='checkbox' name='check[]' value=".$elem["id"].">".$form."</td>";
										}
										echo "</tr>";
									}
							}	
						?>
						
					</tbody>
					</table>
				</div>
				<?php
					if (( $id_access==0) and (isset($BD_submit)) ){
						echo '<input type="submit" name="update" value="Отправить в МНС" title="отправить">';
						echo '<input type="button" name="create" onclick="check_click()" value="Сформировать отчет" title="Сформировать отчет">';
						echo '<input type="button" name="createavto" onclick="create_click()" value="Сформировать автоматически" title="Сформировать автоматически">';
					}
				?>
			</form>
		
			<form id="hides" method="post" autocomplete="off" target="_self" style="display:none" action="update_fild.php">
				<input type="text" id='id_fild' name="id_fild">
				<input type="submit" name='sub_id_fild'>
			</form>
			
			<form id="hides2" method="post" autocomplete="off" target="_blank" style="display:none" action="otchet.php">
				<input type="text" id='id_fild' name="sub_create" value="1">
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
				<input type="checkbox" name="req[]" value="address">Адрес ИМНС<BR>
				<input type="checkbox" name="req[]" value="mail">E-mail ИМНС<BR>
				<input type="checkbox" name="req[]" value="unp">УНП ИМНС<BR>
				<p><input type="submit" name="report" value="Сформировать" title="Сформировать отчет"></p>
			</form>
		</div>
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