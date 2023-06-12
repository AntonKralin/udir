<?php
session_start();
session_unset();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';
$bd = new BD();
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
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	</head>

	<body>
		<div class ="body_div">
			<div id="work_index">
			<!-- <hr> -->
			<br>
			<p><h3 style="text-align:center; background:#66cc99; font-size:18pt; padding:1em; font-style: italic;">«Учет доступа к информационным ресурсам» Витебская область</h3></p>
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
                                                $adminsDAO = new AdminsDAO();
						$admins = $adminsDAO->getAdminByLogin($bd,$_POST["login"]);
						$data=[];
						if ($admins->password != $_POST["password"]){
                                                    echo "<p>неправильный логин\пароль</p>";
						}else{
                                                    #echo "<p>правильный логин\пароль</p>";
                                                    $imnsDAO = new ImnsDAO();
                                                    $imns = $imnsDAO->getImnsById($bd,$admins->id_imns);
                                                    $imnsList = $imnsDAO->getImnsList($bd, $imns);
                                                    $regionDAO = new RegionDAO();
                                                    $region = $regionDAO->getRegionByID($bd,$imns->id_region);
                                                    
                                                    
                                                    $_SESSION["admins"] = serialize($admins);
                                                    $_SESSION["imns"] = serialize($imns);
                                                    $_SESSION["imnsList"] = serialize($imnsList);
                                                    $_SESSION["region"] = serialize($region);
                                                    $_SESSION["start"] = "1";
                                                    header("Location: main.php");
                                                    exit;
                                                }
                                        }				
				?>
				<br><br><br>
				
			</form>
			
			</div>
		</div>
	
	</body>
	
</html>