<?php
session_start();
include 'connect.php';
include 'const.php';

$id_access = null;

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

if (isset ($_POST["upd"])){
	$update_query = "UPDATE `table_user` SET ";
	$update_query .= " `fio` = '".mysqli_real_escape_string($link, $_POST['fio'])."', ";
	$update_query .= " `unit` = '".mysqli_real_escape_string($link, $_POST['unit'])."', ";
	$update_query .= " `job` = '".mysqli_real_escape_string($link, $_POST['job'])."', ";
	$update_query .= " `telefon` = '".mysqli_real_escape_string($link, $_POST['telefon'])."', ";
	$update_query .= " `ip` = '".mysqli_real_escape_string($link, $_POST['ip'])."' ";
	//$update_query .= " `comp_name` = '".mysqli_real_escape_string($link, $_POST['comp_name'])."', ";
	//$update_query .= " `ad_login` = '".mysqli_real_escape_string($link, $_POST['ad_login'])."' ";
	$update_query .= " WHERE `table_user`.`id` = ".mysqli_real_escape_string($link, $_POST['id']).";";
	//echo $update_query;
	mysqli_query($link,$update_query) or die(mysqli_error($link));
	header("Location: main.php");
	exit;
}

if (isset ($_POST["delete"])){
	$delete_query = "UPDATE `table_user` SET `del`=0 WHERE `table_user`.`id` = ".mysqli_real_escape_string($link, $_POST['id']);
	mysqli_query($link,$delete_query) or die(mysqli_error($link));
	header("Location: main.php");
	exit;
}

if (isset ($_POST["activate"])){
	$delete_query = "UPDATE `table_user` SET `del`=1 WHERE `table_user`.`id` = ".mysqli_real_escape_string($link, $_POST['id']);
	mysqli_query($link,$delete_query) or die(mysqli_error($link));
	header("Location: main.php");
	exit;
}

if (isset ($_POST["del_user"])){
	$del_base_query = "DELETE FROM `table_fild` WHERE `table_fild`.`id_user` = ".mysqli_real_escape_string($link, $_POST['id']);
	mysqli_query($link,$del_base_query) or die(mysqli_error($link));
	$del_base_query = "DELETE FROM `table_user` WHERE `table_user`.`id` = ".mysqli_real_escape_string($link, $_POST['id']);
	mysqli_query($link,$del_base_query) or die(mysqli_error($link));
	header("Location: main.php");
	exit;
}

?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Редактирование пользователя</title>
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
		<div class ="body_div">
			<div class ="center_div">
			<form class="user_update_form" autocomplete="off" method="post" action="update_user.php">
			<?php 
				if (isset ($_POST['select_update_user'])){
					$user_query = "SELECT * FROM `table_user` WHERE `id` = ".mysqli_real_escape_string($link, $_POST['select_update_user']);
					$result = mysqli_query($link,$user_query) or die(mysqli_error($link));
					$users=[];
					for ($users=[]; $row=mysqli_fetch_assoc($result); $users[]=$row);
					foreach($users as $elem){
						echo "<br><H2>Редактирование пользователя</H2>";
						echo "<p><label><h8>Фамилия Имя Отчество:</h8></label><input type='text' pattern='^[А-Яа-яЁё\s]+$' required placeholder='ФИО' title='".$user_title['fio']."' name='fio' value='".$elem['fio']."'><label><h7>".$user_title['fio']."</h7></label></p>";
						echo "<p><label><h8>Подразделение:</h8></label><input type='text' required placeholder='Подразделение' title='".$user_title['unit']."' name='unit' value='".$elem['unit']."'><label><h7>".$user_title['unit']."</h7></label></p>";
						//echo "<p><label><h8>Должность:</h8></label><input type='text' required placeholder='Должность' title='".$user_title['job']."' name='job' value='".$elem['job']."'><label><h7>".$user_title['job']."</label></p>";
						echo '<p><h8>Должность:</h8><br><select name="job" >';
						for ($i=0; $i<count($job_state);$i++){
							$sel="";
							if ($job_state[$i]==$elem["job"]){$sel='selected="selected"';}
							echo "<option value='".$job_state[$i]."' ".$sel.">".$job_state[$i]."</option>";
						}
						echo '</select></p>';
						echo "<p><label><h8>Рабочий телефон:</h8></label><input type='text' pattern='8[0-9]{5,}' required placeholder='Телефон' title='".$user_title['telefon']."' name='telefon' value='".$elem['telefon']."'><label><h7>".$user_title['telefon']."</h7></label></p>";
						echo "<p><label><h8>IP-адрес компьютера:</h8></label><input type='text' required pattern='\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}' placeholder='IP' title='".$user_title['ip']."' name='ip' value='".$elem['ip']."'><label><h7>".$user_title['ip']."</h7></label></p>";
						//echo "<p><input type='text'  placeholder='Имя компьютера' title='".$user_title['comp_name']."' name='comp_name' value='".$elem['comp_name']."'><label><h7>".$user_title['comp_name']."</h7></label></p>";
						//echo "<p><input type='text'  placeholder='Логин AD' title='".$user_title['ad_login']."' name='ad_login' value='".$elem['ad_login']."'><label><h7>".$user_title['ad_login']."</h7></label></p>";
						echo "<hr><p><H8 style='color:brown'>!!! Обратите внимание !!!</H8><h7><br>1) Перед блокировкой пользователя убедитесь, в прекращении доступов к базам.<br>2) В случае смены подразделения заявки на изменение реквизитов создавать не нужно (кроме СККС).<br>3) Для предоставления доступа к СККС необходимо наличие бумажного варианта карточки открытого ключа.</h7><br><br></p>";
						echo "<input type='text' name='id' style='display:none' value='".$elem['id']."'>";
					}
				}
			?>
				<p><input type="submit" class="button" name="upd" value="Сохранить изменения" title="Сохранить изменения"></p>
				<p><input type="submit" class="button" name="delete" value="Заблокировать пользователя" title="Заблокировать пользователя"></p>
				<?php 
					echo '<p><input type="submit" class="button" name="activate" value="Активировать пользователя" title="Активировать пользователя"></p>';
					if ($id_access == 0){
						echo '<p><input type="submit" class="button" name="del_user" value="Удалить пользователя" title="Удалить пользователя"></p>';
					}
				?>
			</form>
			<?php 
				$go = "go_to_main_click('".$_SESSION["HTTP_REFERER"]."')";
				echo '<p><button class="button" onclick="'.$go.'" title="Выйти на главную страницу">Назад</button></p>';
			?>
			
			<br>
			</div>
		</div>
	</body>
</html>	