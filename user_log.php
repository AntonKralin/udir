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
                        <td>IP</td>
                        <td>Телефон</td>
                        <td>Должность</td>
                        <td>Изменено</td>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $userDAO = new UsersDao();
                        $jobDAO = new JobsDAO();
                        $changeUserDAO = new Change_usersDAO();
                        $inputs = filter_input_array(INPUT_POST);
                        
                        
                        if (isset($inputs['userLogId'])){
                            $userId = $inputs['userLogId'];
                            $user = $userDAO->getUsersById($bd, $userId);
                            $job = $jobDAO->getJobsById($bd, $user->id_jobs);
                            
                            echo "<tr>";
                            echo "<td style='color:brown;'>".$user->fio."</td>";
                            echo "<td style='color:brown;'>".$user->ip."</td>";
                            echo "<td style='color:brown;'>".$user->telefon."</td>";
                            echo "<td style='color:brown;'>".$job->name."</td>";
                            echo "<td style='color:brown;'>".""."</td>";
                            echo "</tr>";
                            
                            $changeUserList = $changeUserDAO->getUsersByIdUsers($bd, $user->id);
                            foreach ($changeUserList as $elem){
                                $job = $jobDAO->getJobsById($bd, $elem->id_jobs);
                                echo "<tr>";
                                echo "<td>".$elem->fio."</td>";
                                echo "<td>".$elem->ip."</td>";
                                echo "<td>".$elem->telefon."</td>";
                                echo "<td>".$job->name."</td>";
                                echo "<td>".$elem->change_date."</td>";
                                echo "</tr>";
                            }
                        }

                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>

