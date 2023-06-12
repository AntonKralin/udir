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
                    <table border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
                    <thead>
                            <tr>
                            <td width="3%" >Код ИМНС</td>
                            <td width="13%" >ФИО</td>
                            <td width="13%">Должность</td>
                            <td width="3%">Телефон</td>
                            <td width="3%">IP</td>
                            <td width="3%">Логин БД</td>
                            <td width="3%">Дата предоставления</td>
                            <td width="3%">Дата прекращения (окончания)</td>
                            <td width="3%">Дата выгрузки</td>
                            <td width="3%">Статус</td>
                            <td >Примечание</td>
                            <td width="5%">ИР</td>
                    </tr>
                    </thead>
                    <tbody class="table_body">
                        <?php 
                            $inputs = filter_input_array(INPUT_GET);
                            if (isset($inputs['selectUser'])){
                                $id_user = $inputs['selectUser'];
                                $baseDAO = new BaseDAO();
                                $baseList = $baseDAO->getBaseList($bd, $region->id);
                                $requestionDAO = new RequestionDAO();
                                
                                if ($id_user != 0){
                                    $requestionList = $requestionDAO->getRequestionByUser($bd, $id_user);
                                }else{
                                    $requestionList = $requestionDAO->getRequestionByImns($bd, $imns->id);
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
                                    echo '<td>'.$user->telefon.'</td>';
                                    echo '<td>'.$user->ip.'</td>';
                                    echo '<td>'.$request->login.'</td>';
                                    echo '<td>'.$request->date_from.'</td>';
                                    echo '<td>'.$request->date_to.'</td>';
                                    echo '<td>'.$request->date_upload.'</td>';
                                    echo '<td>'.$request->state.'</td>';
                                    echo '<td>'.$request->notice.'</td>';
                                    echo '<td>'.$irName.'</td>';
                                    echo '</tr>';
                                }
                            }
                        ?>
                    </tbody>    
                    </table>
                    
                    <br><br>
                    <H3>Локальные базы</H3>
                    <table border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
                        <thead>
                            <tr>
                                <td width="13%">ФИО</td>
                                <td width="5%">IP</td>
                                <td width="5%">Дата предоставления</td>
                                <td width="5%">Дата окончания</td>
                                <td width="5%">Дата исполнения</td>
                                <td width="7%">Статус</td>
                                <td width="7%">Номер</td>
                                <td >Примечание</td>
                                <td width="5%">ИР</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $inputs = filter_input_array(INPUT_GET);
                                if (isset($inputs['selectUser'])){
                                    $id_user = $inputs['selectUser'];
                                    $localBaseDAO = new Local_baseDAO();
                                    $localRequestDAO = new Local_requestionDAO();
                                    $localRequestionList = $localRequestDAO->getReqestionByUser($bd, $id_user);
                                    
                                    foreach ($localRequestionList as $local){
                                        echo '<tr>';
                                        echo '<td>'.$user->fio.'</td>';
                                        echo '<td>'.$user->ip.'</td>';
                                        echo '<td>'.$local->date_from.'</td>';
                                        echo '<td>'.$local->date_to.'</td>';
                                        echo '<td>'.$local->date_do.'</td>';
                                        echo '<td>'.$local->state.'</td>';
                                        echo '<td>'.$local->number.'</td>';
                                        echo '<td>'.$local->notice.'</td>';
                                        
                                        $localBase = $localBaseDAO->getBaseById($bd, $local->id_local_base);
                                        echo '<td>'.$localBase->name.'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
            </div>            
	
	</body>
	<script type="text/javascript" src="js/sort.js"></script>
</html>
