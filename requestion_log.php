<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
$bd = new BD();
}else{
    exit;
}

?>
<html>

	<head>
            <title>«Учет доступа к информационным ресурсам»</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Учет доступа к информационным ресурсам" />
            <meta name="keywords" content="Учет доступа к информационным ресурсам" />
            <meta name="viewport" content="width=device-width">
            <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
            <link rel="stylesheet" href="styles/main.css?5" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?5"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
	</head>

	<body>
            <div id="work_all">
                <table border="1" width="100%" cellpadding="1" cols="4" class="sortable" >
                <thead>
                    <tr>
                        <td>ФИО</td>
                        <td>База</td>
                        <td>Изменения</td>
                        <td>Примечание</td>
                        <td>Пользователь</td>
                        <td>Дата изменения</td>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $userDAO = new UsersDao();
                        $baseDAO = new BaseDAO();
                        $adminsDAO = new AdminsDAO();
                        $changeReq = new Change_requestionDAO();
                        $inputs = filter_input_array(INPUT_GET);
                        
                        $chageList = null;
                        if ($inputs['id_base'] == ""){
                            $chageList = $changeReq->getRequestionListByUser($bd, $inputs['id_user']);
                        }else{
                            if ($inputs["id_user"]=="0"){
                                $chageList = $changeReq->getRequestionListByBase($bd, $inputs['id_base']);
                            }else{
                                $chageList = $changeReq->getRequestionListByUserBase($bd, $inputs['id_user'], $inputs['id_base']);
                            }
                        }
                        foreach ($chageList as $change){
                            $user = $userDAO->getUsersById($bd, $change->id_user);
                            $base = $baseDAO->getBaseById($bd, $change->id_base);
                            $admin = $adminsDAO->getAdminById($bd, $change->id_admins);
                            
                            echo "<tr>";
                            echo "<td>".$user->fio."</td>";
                            echo "<td>".$base->shot_name."</td>";
                            echo "<td>".$change->filds."</td>";
                            echo "<td>".$change->notice."</td>";
                            echo "<td>".$admin->login."</td>";
                            echo "<td>".$change->date_change."</td>";
                            echo "</tr>";
                            
                        }
                        

                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>

