<?php
session_start();
session_unset();
include 'connect.php';
include 'const.php';
?>
<html>

	<head>
		<title>«Учет доступа к информационным ресурсам» | Авторизация</title>
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
			<div id="work_index">
			<!-- <hr> -->
			<br>
			<p><h3 style="text-align:center; background:#66cc99; font-size:18pt; padding:1em;">«Учет доступа к информационным ресурсам»</h3></p>
			<form id="pass" method="post" action="index.php">
				<p><h2>Авторизуйтесь:</h2></p>
				Логин:
				<input type="text" name="login" style="width:300px">
				Пароль:
				<input type="password" name="password" style="width:300px">
				<p></p>
				<input class="button" style="width:147px" type="submit" name="submit" value="Войти" title="Войти">
				<input class="button" style="width:147px" type="reset" value="Очистить">
				<?php 
					if (isset($_POST['submit'])){
						#echo "submit";
						$query = 'select `id_mns`,`login`,`password`, `access` from `table_admins` where `login` like "'.mysqli_real_escape_string($link, $_POST["login"]).'"';
						$result = mysqli_query($link,$query) or die(mysqli_error($link));
						
						$data=[];
						for ($data=[]; $row=mysqli_fetch_assoc($result); $data[]=$row);
						if (count($data) != 0){	
							$elem = [];
							foreach($data as $elem);
							if ($elem["password"]!=$_POST["password"]){
								echo "<p>неправильный логин\пароль</p>";
							}else{
								#echo "<p>правильный логин\пароль</p>";
								$_SESSION["id_access"] = $elem["access"];
								$_SESSION["id_mns"] = $elem["id_mns"];
								$_SESSION["start"] = "1";
								header("Location: main.php");
								exit;
							}
						}else{
							echo "<p>неправильный логин\пароль</p>";
						}
					}				
				?>
				<br><br><br>
				
			</form>
			
			</div>
		</div>
	
	</body>
	
</html>