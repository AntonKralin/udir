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

$select_imns = $imns->id;
if ( isset( $_SESSION['select_imns'] ) ){
    $select_imns = $_SESSION['select_imns'];
}
$bd = new BD();
}else{
    exit;
}

?>
<html>

	<head>
            <title>«Учет доступа к информационным ресурсам» | Отчет по корректировкам</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Учет доступа к информационным ресурсам" | Отчет по корректировкам />
            <meta name="keywords" content="Учет доступа к информационным ресурсам" | Отчет по корректировкам />
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
            <script type="text/javascript" src="js/sort.js"></script>
	</head>

	<body>
            <div id="work_all">
                <table border="1" width="100%" cellpadding="1" cols="8" class="sortable" >
                <thead>
                    <tr>
                        <td width="3%" >Код ИМНС</td>
                        <td width="7%">Ф.И.О</td>
                        <td width="4%">Должность</td>
                        <td width="5%">Имя пользователя ИР</td>
                        <td width="3%">Дата изменений</td>
                        <td width="8%">Состояние</td>
                        <td width="7%">IP-адрес</td>
                        <td width="5%">ИР</td>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $requestionDAO = new RequestionDAO();
                        $requestionList = null;
                        $baseDAO = new BaseDAO();
                        $baseList = $baseDAO->getBaseList($bd, $region->id);
                        
                        if ($select_imns == 0 ){
                            $requestionList = $requestionDAO->getRequestionByStateRegion($bd, "выгружен на область", $region->id);
                        }else{
                            $requestionList = $requestionDAO->getRequestionByStateImns($bd, 'выгружен на область', $imns->id);
                        }
                        
                        foreach ($requestionList as $request){
                            echo '<tr>';
                                    $imnsNumbe="";
                                    foreach ($imnsList as $elem){
                                        if ($elem->id == $request->id_imns){
                                            $imnsNumber = $elem->number;
                                        }
                                    }
                                    $irName="";
                                    foreach ($baseList as $base){
                                        if ($base->id == $request->id_base){
                                            $irName = $base->shot_name;
                                        }
                                    }
                                    $userDAO = new UsersDao();
                                    $user = $userDAO->getUsersById($bd, $request->id_user);
                                    $jobDAO = new JobsDAO();
                                    $job = $jobDAO->getJobsById($bd, $user->id_jobs);
                                    echo '<td>'.$imnsNumber.'</td>';
                                    echo '<td>'.$user->fio.'</td>';
                                    echo '<td>'.$job->name.'</td>';
                                    echo '<td>'.$request->login.'</td>';
                                    echo '<td>'.convertDate($request->date_upload).'</td>';
                                    echo '<td>'.$request->request.'</td>';
                                    echo '<td>'.$user->ip.'</td>';
                                    echo '<td>'.$irName.'</td>';
                                    echo '</tr>';
                        }
                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>

