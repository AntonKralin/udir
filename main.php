<?php 
session_start();
$id_access=null;
$id_mns = null;
include 'connect.php';
include 'const.php';

$_SESSION["HTTP_REFERER"] = 'main.php';


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

if (isset($_POST['add'])){
	switch ($_POST['add']){
		case 'Создать ИР':
				$add_base_query = "INSERT INTO `table_base` (`id`, `name`, `shot_name`) VALUES (NULL,";
				$add_base_query .= " '".mysqli_real_escape_string($link, $_POST["base_name"])."',";
				$add_base_query .= " '".mysqli_real_escape_string($link, $_POST["shot_name"])."');";
				mysqli_query($link,$add_base_query) or die(mysqli_error($link));
			break;
						
		case 'Добавить':
				$users_query = "SELECT count(*) FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." and `fio`='".$_POST["FIO"]."'";
				$rez_count = mysqli_query($link,$users_query) or die(mysqli_error($link));
				$count=[];
				$con = 0;
				for ($count=[]; $row=mysqli_fetch_assoc($rez_count); $count[]=$row);
				foreach ($count as $elem)
					$con = $elem["count(*)"];
				if ($con > 0){
					
				}else{
					$user_query = "INSERT INTO `table_user` (`id`, `id_mns`, `fio`, `ip`, `telefon`, `unit`, `job`) VALUES (NULL,";
					$user_query .= " '".mysqli_real_escape_string($link, $id_mns)."',";
					$user_query .= " '".mysqli_real_escape_string($link, $_POST["FIO"])."',";
					$user_query .= " '".mysqli_real_escape_string($link, $_POST["IP"])."',";
					//$user_query .= " '".mysqli_real_escape_string($link, $_POST["COMP"])."',";
					//$user_query .= " '".mysqli_real_escape_string($link, $_POST["AD"])."',";
					$user_query .= " '".mysqli_real_escape_string($link, $_POST["TEL"])."',";
					$user_query .= " '".mysqli_real_escape_string($link, $_POST["unit"])."',";
					$user_query .= " '".mysqli_real_escape_string($link, $_POST["job"])."');";
					mysqli_query($link,$user_query) or die(mysqli_error($link));
				}
			break;
	}
}

if (isset($_POST['delete'])){
	switch ($_POST['delete']){
		case 'Удалить базу':
				$del_base_query = "DELETE FROM `table_base` WHERE `table_base`.`id` = ".mysqli_real_escape_string($link, $_POST["select_base_dialog"]);
				mysqli_query($link,$del_base_query) or die(mysqli_error($link));		
			break;
	}
}

?>

<html>

	<head>
		<title>«Учет доступа к информационным ресурсам»</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="Учет доступа к информационным ресурсам. Витебская область" />
		<meta name="keywords" content="Учет доступа к информационным ресурсам. Витебская область" />
		<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link rel="stylesheet" href="styles/main.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
		<link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />		
	
		<script type="text/javascript" src="js/jquery1.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.session.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript"> 
			function validate_form(){
				var valid = true;
				if ( document.getElementById("job").value=="0" ){
					alert("Введите должность");
					valid=false;
				}
				return valid;
			}
			
			$(function(){
			$("#popup_date").dialog({
				autoOpen:false,
				width: 800,
				height: "auto"
			});
		});
		</script>
	
	</head>

	<body>
		
		<form id="imns_label" method="post" autocomplete="off" action="main.php">
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
			<H9>«Учет доступа к информационным ресурсам»</H9>
			<a href="index.php" id="log_out" class="button">ВЫЙТИ</a>
		</div>
			
		
		<div id="tabs">
			<div class="tab"> 
			<form method="GET" autocomplete="off" action="main.php">
				<?php 
					foreach($tabs_base as $elem){
						if ( ($id_mns>1) && (($elem['id']==21) or ($elem['id']==23))) continue;
						$col="";
						if ($elem["shot_name"]==$BD_submit)
							$col=" style='color:white; background-color:#669999'' ";
						echo "<input type='submit' ".$col." value='".$elem["shot_name"]."' name='BD' title='".$elem["name"]."'>";
					}
				?>
			</form>	
			</div>
			
			<div id="work">
				<table border="1" width="100%" cellpadding="1" cols="12" class="sortable" >
				
				<thead>
					<tr>
						<?php 
							if ($id_access == 0 ){
							echo '<td width="2%" >ИМНС</td>';
							}
						?>
						<td width="13%" >ФИО</td>
						<td width="13%">Подразделение</td>
						<td width="13%">Должность</td>
						<td width="4%">Телефон</td>
						<td width="5%">IP</td>
						<!-- <td width="8%">Имя компьютера</td> -->
						<!-- <td width="7%">Логин AD</td> -->
						<td width="7%">Логин БД</td>
						<td width="5%">Дата предоставления</td>
						<td width="5%">Дата окончания</td>
						<td width="5%">Дата выгрузки</td>
						<td width="7%">Статус</td>
						<td >Примечание</td>
						<td width="1%"> </td>
					</tr>
				</thead>
				<tbody >	
					
					<?php 
						if (isset($BD_submit)){
							
							$base_id = "";
							foreach($tabs_base as $elem){
								if ($elem["shot_name"] == $BD_submit)
									$base_id = $elem["id"];
							}
							//echo $base_id;
							//echo $id_mns;
							$all_mns="";
							if ($id_mns != 0){
								$all_mns = " and `table_fild`.`id_mns` = ".mysqli_real_escape_string($link, $id_mns);
							}

							$table_query = "SELECT `table_user`.`fio`, `table_imns`.`number`, `table_user`.`ip`, `table_user`.`comp_name`, `table_user`.`ad_login`, `table_user`.`telefon`, `table_user`.`unit`, `table_user`.`job`, `table_user`.`del`, `table_fild`.`id_user`, `table_fild`.`id`, `table_fild`.`request`, `table_fild`.`id_base`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_fild`.`date_next`, `table_fild`.`upload`, `table_fild`.`notice` from `table_user`, `table_imns`, `table_fild` WHERE `table_fild`.`state`!='".$fild_state[3]."' and `table_fild`.`state`!='".$fild_state[4]."' and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns." and `table_fild`.`id_mns`=`table_imns`.`id` and `table_fild`.`id_base` = '".mysqli_real_escape_string($link, $base_id)."' ORDER BY `table_user`.`fio`";
							$result = mysqli_query($link,$table_query) or die(mysqli_error($link));
							$table=[];
							$tdata = date("d.m.Y",strtotime("+2 month"));
							$ndata = date("d.m.Y");
							for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
								foreach($table as $elem){
									//echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
									echo "<tr>"; 
									$col="";
									$col2="";
									if ($elem["del"]==0){
										$col = " style='color:brown;' ";
									}
									
									$pos=strpos($elem['date_to'],".");
									if($pos===false) {}else{
										$d1 = strtotime($tdata);
										$d2 = strtotime($elem['date_to']);
										$d3 = strtotime($ndata);
										//2 month
										if (($d1>$d2) && ($d2>$d3)){
											//$col2= " style='background-color: blue;' ";
											$col2= " style='color:orange;' ";
										}
										//expired
										if (($d3>$d2)){
											//$col2= " style='background-color: blue;' ";
											$col2= " style='color:orange;' ";
										}
									}
									if ($id_access == 0 ){
										echo "<td>".$elem["number"]."</td>";;
									}
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
									if ($id_access != 2){
										echo '<td><input class="button" type="button" onclick="edit_fild('.$elem["id"].')" value="✎" title="Изменить доступ"></td>';
									}	
									echo "</tr>";
								}
						}	
					?>
					
				</tbody>
				</table>
			</div>
			
			
		</div>
		
		<div id="bottom">
		
			<?php 
				if (($id_access == 0) || ($id_access == 1)){
					echo '<button id="user" onclick="user_click();"> Пользователи</button>';
					echo '<button id="fild" onclick="fild_click();"> Доступы</button>';
					echo '<button id="unloaded_obl" onclick="otcheti_click();"> Отчеты</button>';
				}
			
				if (($id_access == 0)){
					echo '<button id="add_base" onclick="admin_click();"> НСИ </button> ';
				}
			?>
		</div>
				
	<form id="hides" method="post" autocomplete="off" target="_self" style="display:none" action="update_fild.php">
		<input type="text" id='id_fild' name="id_fild">
		<input type="submit" name='sub_id_fild'>
	</form>	
	
	<div id="user_dialog" style="display:none;" title="Пользователи">
		<form id="user_form" autocomplete="off" onsubmit="return validate_form();" method="post" action="main.php">
			<H3>Добавить пользователя: </H3>
			<p><input type="text" pattern='^[А-Яа-яЁё\s]+$' required name="FIO" title='<?php echo $user_title['fio']?>' placeholder="Фамилия Имя Отчество" style="width:470px"/></p>
			<p><input type="text" required name="unit" title='<?php echo $user_title['unit']?>' placeholder="Подразделение"  style="width:470px"/></p>
			<!--<p><input type="text" required name="job" title='<?php echo $user_title['job']?>' placeholder="Должность (в нижнем регистре)" style="width:470px"/></p>-->
			<p><select style="width:470px" name="job" id="job">
				<option value="0" disabled selected>Выберите должность</option>
				<?php
					for ($i=0; $i<count($job_state);$i++){
						echo "<option value='".$job_state[$i]."'>".$job_state[$i]."</option>";
					}
				?>
			</select></p>	
			<p><input type="text" required pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" name="IP" title='<?php echo $user_title['ip']?>' placeholder="IP адрес (xxx.xxx.xxx.xxx)" style="width:470px"/></p>
			<!-- <p><input type="text" name="COMP" title='<?php echo $user_title['comp_name']?>' placeholder="Имя комьютера"  size="60px"/></p> -->
			<!-- <p><input type="text" name="AD" title='<?php echo $user_title['ad_login']?>' placeholder="Логин AD"  size="60px"/></p> -->
			<p><input type="text" pattern='8[0-9]{5,}' required name="TEL" title='<?php echo $user_title['telefon']?>' placeholder="Рабочий телефон (8xxxxxxxxxx)"  style="width:470px"/></p>
			<p><input type="submit" name="add" value="Добавить" title="Добавить пользователя"></p>
		</form>
			
		<form id="update_user_form"  method="post" action="update_user.php">
			<hr>
			<H3>Редактировать пользователя:</H3>
			<select name="select_update_user" >
				<option value="0" disabled selected >Выберите пользователя</option>
				<?php 
					
					$user_query = "SELECT * FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." and `del`=1 ORDER BY `fio`";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
					}
				?>
			</select>
			<input type="submit" name="update" value="Изменить" title="Изменить пользователя">
		</form>	
		<form id="delete_user_form"  method="post" action="update_user.php">
			<hr>
			<H3>Удаленные пользователи:</H3>
			<select name="select_update_user" >
				<option value="0" disabled selected>Выберите пользователя</option>
				<?php 
					
					$user_query = "SELECT * FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." and `del`=0 ORDER BY `fio`";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
					}
				?>
			</select>
			<input type="submit" name="update" value="Изменить" title="Изменить пользователя">
		</form>	
	</div>
		
	<div id="add_fild" style="display:none;" title="Доступы">
		<form id="user_fild" autocomplete="off" method="post" action="update_fild.php">
			<h3>Корректировки:</h3>
			<select id="select_user" name="select_user" >
				<option value="0"  disabled selected>Выберите пользователя</option>
				<?php 
					
					$user_query = "SELECT * FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." and `del`=1 "." ORDER BY `fio`";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
					}
				?>
			</select></p>
			<p><select name="select_base">
				<option value="0" disabled selected>Выберите базу</option>
				<?php
					foreach($tabs_base as $elem){
						echo "<option value=".$elem['id'].">".$elem['shot_name']."</option>";
					}
				?>
			</select>
			<input type="text" name="notice" title='<?php echo $fild_title['notice']?>' placeholder="Примечание" style="width:310px"/></p>
			<input type="submit" name="add" value="Добавить доступ" title="Добавить доступ">
			<input type="submit" name="update" value="Изменить доступ" title="Изменить доступ">
		</form>
		<hr>
		<form id="svod_fild" autocomplete="off" target="_blank" method="get" action="svod.php">
			<h3>Сводная таблица:</h3>
				<select id="select_user" name="user" >
				<option value="0" selected>Выберите пользователя</option>
				<?php 
					
					$user_query = "SELECT * FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." and `del`=1 "." ORDER BY `fio`";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
					}
				?>
			</select>
			<input type="submit" id="svod" value="Сформировать" title="Сводная таблица"><br>__________<br><h7>*Для формирования сведений по всем работникам не заполняйте поле "Выберите пользователя"</h7>
		</form>
		
		<hr>
		<p><button id="close_fild" onclick="close_fild_click();">Прекращенный доступ</button>
		<button id="popup_alert_click" onclick="popup_alert_click();">Истекает доступ</button></p>
	</div>
	
	<div id="update_fild" style="display:none" title="Редактировать заявку">
		<form id="update_user_form"  method="post" action="update_fild.php">
			<p><select name="select_update_user" >
				<option value="0" disabled selected>Выберите пользователя</option>
				<?php 
					
					$user_query = "SELECT `fio`, `id`  FROM `table_user` WHERE `id_mns` = ".mysqli_real_escape_string($link, $id_mns)." ORDER BY `fio`";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['fio']."</option>";
					}
				?>
			</select></p>
			<p><select name="select_update_base" >
				<option value="0" disabled selected>Выберите базу</option>
				<?php 
					
					$user_query = "SELECT * FROM `table_base` ";
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<option value=".$elem['id'].">".$elem['shot_name']."</option>";
					}
				?>
			</select><p>
			<input type="submit" name="update" value="изменить заявку" title="Изменить">
		</form>
	</div>
	
	<div id="administrirovanie" style="display:none" title="НСИ">
		<p><button id="unloaded" onclick="log_click();">Аудит действий</button></p>
		<hr>
		<form id="base_form" method="post" action="main.php">
			<h3>Информационные ресурсы:</h3>
			<select name="select_base_dialog">
				<option value="0" disabled selected>Выберите ИР</option>
				<?php
					foreach($tabs_base as $elem){
						echo "<option value=".$elem['id'].">".$elem['shot_name']."</option>";
					}
				?>
			</select>
			<p><input type="text" name="base_name" title="Название" placeholder="Наименование" size="28px"/>
			<input type="text" name="shot_name" title="Короткое название" placeholder="Сокращенное наименование" size="29px"/></p>
			<input type="submit" name="add" value="Создать ИР" title="Войти">
			<input type="submit" name="delete" value="Удалить ИР" title="удалить">
		</form>
	</div>
	
	<div id="otcheti" style="display:none" title="Отчеты">
		<p><button id="unloaded_obl" onclick="unloaded_obl_click();"> Отчет по корректировкам</button></p>
		<p><button id="unloaded_obl" onclick="unloaded_mns_click();"> Отчет отправленным в мнс</button></p>
		<hr>
		<div id="otchetall_div">
			<input type='checkbox' name='check_all' />Выделить все
			<form id="form_otchet" target="_blank"  method="post" action="otchetall.php">
				<?php 
					foreach($tabs_base as $elem){
						echo '<input type="checkbox" name="req[]" value="'.$elem["id"].'">'.$elem["shot_name"].'<BR>';
					}
				?>
				<p><input type="submit" name="report" value="Отчет по доступу" title="Сформировать отчет"></p>
			</form>
		</div>
		
		<?php
			if (($id_access == 0)){
				echo "<hr>";
				echo '<p><button id="unloaded" onclick="unloaded_click();"> Выгруженные на область</button></p>';
				echo '<p><button id="unloaded" onclick="count_click();"> Количество</button></p>';
			}
		?>
	</div>
	

	
	<div class="popup_alert" title="В течении 2-х месяцев доступ заканчивается:" id="popup_date">
		<div class="popup-content">
			<table border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
				<thead>
					<tr>
						<td width="3%" >Код ИМНС</td>
						<td width="13%" >ФИО</td>
						<td width="3%">Дата окончания</td>
						<td width="10%">Статус</td>
						<td width="10%">ИР</td>
					</tr>
				</thead>
				<tbody class="table_body">	
					<?php
						$all_mns="";
							if ($id_access != 0){
								$all_mns = " and `table_imns`.`id` = ".mysqli_real_escape_string($link, $id_mns)." ";
							}
						$table_query = "SELECT `table_user`.`fio`, `table_user`.`ip`, `table_base`.`shot_name`, `table_user`.`comp_name`, `table_user`.`ad_login`, `table_imns`.`number`, `table_user`.`telefon`, `table_user`.`unit`, `table_user`.`job`, `table_user`.`del`, `table_fild`.`id_user`, `table_fild`.`id`, `table_fild`.`request`, `table_fild`.`id_base`, `table_fild`.`login`, `table_fild`.`date_from`, `table_fild`.`date_to`, `table_fild`.`state`, `table_fild`.`date_next`, `table_fild`.`upload`, `table_fild`.`notice` from `table_user`, `table_fild`, `table_base`, `table_imns` WHERE `table_fild`.`state`='".$fild_state[2]."'  and `table_fild`.`id_base` = `table_base`.`id` and `table_imns`.`id`=`table_fild`.`id_mns`  and `table_fild`.`id_user`=`table_user`.`id` ".$all_mns;
						$result = mysqli_query($link,$table_query) or die(mysqli_error($link));
						$table=[];
						for ($table=[]; $row=mysqli_fetch_assoc($result); $table[]=$row);
							foreach($table as $elem){
								
								$tdata = date("d.m.Y",strtotime("+2 month"));
								$ndata = date("d.m.Y");
								//echo $tdata;
								$pos=strpos($elem['date_to'],".");
								if($pos===false) {}else{
									
									$d1 = strtotime($tdata);
									$d2 = strtotime($elem['date_to']);
									$d3 = strtotime($ndata);
									echo "<tr>";
									if (($d1>$d2) && ($d2>=$d3)){
										echo "<td>".$elem["number"]."</td>";
										echo "<td >".$elem["fio"]."</td>";
										echo "<td>".$elem["date_to"]."</td>";
										echo "<td>".$elem["state"]."</td>";
										echo "<td>".$elem["shot_name"]."</td>";
									}
									echo "</tr>";
								}
							}	
					?>
				</tbody>
		</div>
	</div>
	
	<script type="text/javascript" src="js/functions.js?2"></script>
	<script type="text/javascript" src="js/sort.js"></script>

	<script>
		var dataDiv = document.getElementById("otchetall_div");
		var checkItAll= dataDiv.querySelector("input[name='check_all']");
		var inputs = dataDiv.querySelectorAll('form>input');
		
		checkItAll.addEventListener('change', function(){
			if (checkItAll.checked){
				Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=true;})
				
			}else{
				Array.prototype.slice.apply(inputs).forEach(function(input){input.checked=false;})
			}
		});
	
		$(function(){
			var ses = $.session.get('start');
			//alert(ses);
			if (ses!='0'){
				$.session.set('start','0');
				$("#popup_date").dialog("open");
			}
		});
		
		$( "#user_dialog" ).dialog({
			autoOpen: false,
			width: 500
		})
		
		$( "#add_fild" ).dialog({
			autoOpen: false,
			width: 500
		})
		
		$( "#administrirovanie" ).dialog({
			autoOpen: false,
			width: 500
		})
		
		$( "#otcheti" ).dialog({
			autoOpen: false,
			width: 300
		})
		
		$( "#update_user" ).dialog({
			autoOpen: false,
			width: 500
		})		
		
		$( "#update_fild" ).dialog({
			autoOpen: false,
			width: 500
		})		
	</script>
	

	
	</body>
</html>