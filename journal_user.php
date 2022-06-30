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
            <title>«Учет доступа к информационным ресурсам»  | Журнал учета (список) пользователей, имеющих доступ к информационным ресурсам</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Учет доступа к информационным ресурсам" | Отчет по доступу />
            <meta name="keywords" content="Учет доступа к информационным ресурсам" | Отчет по доступу />
            <meta name="viewport" content="width=device-width">
            <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
            <link rel="stylesheet" href="styles/main.css?5" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?5"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/sort.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
	</head>

	<body>
            <div id="work_all">
                <table border="1" width="100%" cellpadding="1" cols="14" class="sortable" >
                <thead>
                    <tr>
                        <td width="3%" >Код ИМНС</td>
                        <td width="13%">Инспекция МНС</td>
                        <td width="13%">Ф.И.О ответственного</td>
                        <td width="13%">Должность</td>
                        <td width="5%">Имя пользователя ИР</td>
                        <td width="5%">Состояние доступа (Д- действующий, О- отключен)</td>
                        <td width="5%">Дата предоставления доступа </td>
                        <td width="5%">Дата отключения доступа </td>
                        <td width="5%">IP-адрес</td>
                        <td width="5%">Наименование ИР</td>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $requestionDAO = new RequestionDAO();
                        $userDAO = new UsersDao();
                        $baseDAO = new BaseDAO();
                        $localBaseDAO = new Local_baseDAO();
                        $localRequestDao = new Local_requestionDAO();
                        $jobDAO = new JobsDAO();
                        $imnsDAO = new ImnsDAO();

                        $baseList = $baseDAO->getBaseList($bd, $imns->id_region);
                        foreach($baseList as $base){
                            if ($select_imns == 0){
                                $requestionList = $requestionDAO->getRequestionByBaseRegion($bd, $base->id, $imns->id_region);
                            }else{
                                $requestionList = $requestionDAO->getRequestionByBaseImns($bd, $base->id, $imns->id);
                            }
                                
                            foreach($requestionList as $request){
                                if ($request->state == $fild_state[4]){
                                    continue;
                                }
                                if ( (($request->state == $fild_state[0]) or ($request->state == $fild_state[1])) and ($request->request == $request_state[0]) ){
                                    continue;
                                }
                                $user = $userDAO->getUsersById($bd, $request->id_user);
                                $job = $jobDAO->getJobsById($bd, $user->id_jobs);
                                $imnsReq = $imnsDAO->getImnsById($bd, $request->id_imns);
                                
                                echo "<tr>";
                                echo "<td>".$imnsReq->number."</td>";
                                echo "<td>".$imnsReq->name."</td>";
                                echo "<td>".$user->fio."</td>";
                                echo "<td>".$job->name."</td>";
                                echo "<td>".$request->login."</td>";
                                if ($request->state == $fild_state[2]){
                                    echo "<td>Д</td>"; 
                                }
                                if ($request->state == $fild_state[3]){
                                    echo "<td>О</td>";
                                }
                                if ( ($request->state == $fild_state[0]) or ($request->state == $fild_state[1]) ){
                                    echo "<td>Д</td>";
                                }
                                echo "<td>".convertDate($request->date_from)."</td>";
                                if ($request->state != $fild_state[3]){
                                    echo "<td> </td>";
                                }else{
                                    echo "<td>".convertDate($request->date_to)."</td>";                                    
                                }
                                echo "<td>".$user->ip."</td>";
                                echo "<td>".$base->shot_name."</td>";
                                echo "</tr>";
                            }    
                        }
                        
                        
                        if ($select_imns == 0){
                            $localRequest = $localRequestDao->getReqestionListByRegion($bd, $imns->id_region);
                        }else{
                            $localRequest = $localRequestDao->getReqestionListByImns($bd, $imns->id);
                        }                        
                        foreach ($localRequest as $request){
                            $base = $localBaseDAO->getBaseById($bd, $request->id_local_base);
                            $user = $userDAO->getUsersById($bd, $request->id_user);
                            $job = $jobDAO->getJobsById($bd, $user->id_jobs);
                            $imnsReq = $imnsDAO->getImnsById($bd, $imns->id);
                            
                            echo "<tr>";
                            echo "<td>".$imnsReq->number."</td>";
                            echo "<td>".$imnsReq->name."</td>";
                            echo "<td>".$user->fio."</td>";
                            echo "<td>".$job->name."</td>";
                            echo "<td> </td>";
                            if ($request->state == "действующий"){
                                echo "<td>Д</td>"; 
                            }else{
                                echo "<td>О</td>";
                            }
                            echo "<td>".convertDate($request->date_from)."</td>";
                            if ($request->state == "действующий"){
                                echo "<td> </td>";
                            }else{
                                echo "<td>".convertDate($request->date_to)."</td>";
                            }
                            echo "<td>".$user->ip."</td>";
                            echo "<td>".$base->name."</td>";
                            echo "</tr>";
                        }
                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>
