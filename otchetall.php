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
            <title>«Учет доступа к информационным ресурсам»  | Отчет по доступу</title>
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
                        <?php 
                                if ($admins->id_access == 3 ){
                                echo '<td width="13%">Инспекция МНС</td>';
                                }
                        ?>
                        <td width="13%">Ф.И.О</td>
                        <td width="13%">Должность</td>
                        <td width="5%">Имя пользователя ИР</td>
                        <td width="5%">Состояние</td>
                        <td width="5%">Дата предоставления (отключения) доступа </td>
                        <td width="5%">IP-адрес</td>
                        <td width="5%">ИР</td>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        if (isset ($_POST["req"])){
                            $requestionDAO = new RequestionDAO();
                            $userDAO = new UsersDao();
                            $baseDAO = new BaseDAO();
                            $jobDAO = new JobsDAO();
                            $imnsDAO = new ImnsDAO();
                            
                            $achek = $_POST["req"];
                            for ($i=0; $i<count($achek); $i++){
                                $base = $baseDAO->getBaseById($bd, $achek[$i]);
                                $requestionList = null;
                                if ($admins->id_access == 3){
                                    if($select_imns == 0){
                                        $requestionList = $requestionDAO->getRequestionByBaseRegion($bd, $achek[$i], $imns->id_region);
                                    }else{
                                        $requestionList = $requestionDAO->getRequestionByBaseImns($bd, $achek[$i], $imns->id);
                                    }
                                    
                                }else{
                                    $requestionList = $requestionDAO->getRequestionByBaseImns($bd, $achek[$i], $imns->id);
                                }
                                
                                foreach($requestionList as $request){
                                    if ($request->state == $fild_state[4]){
                                        continue;
                                    }
                                    if ( ($request->request == $request_state[0]) && (($request->state == $fild_state[0]) || $request->state == $fild_state[1] )){
                                        continue;
                                    }
                                    $user = $userDAO->getUsersById($bd, $request->id_user);
                                    $job = $jobDAO->getJobsById($bd, $user->id_jobs);
                                    $imnsReq = $imnsDAO->getImnsById($bd, $request->id_imns);
                                    
                                    echo "<tr>";
                                    echo "<td>".$imnsReq->number."</td>";
                                    if ($admins->id_access == 3 ){
                                        echo "<td>".$imnsReq->name."</td>";
                                    }
                                    echo "<td>".$user->fio."</td>";
                                    echo "<td>".$job->name."</td>";
                                    echo "<td>".$request->login."</td>";
                                    if ($request->state == $fild_state[3]){
                                        echo "<td>".$fild_state[3]."</td>";
                                        echo "<td>".convertDate($request->date_to)."</td>";
                                    }else{
                                        echo "<td>".$fild_state[2]."</td>";
                                        echo "<td>".convertDate($request->date_from)."</td>";
                                    }
                                    
                                    //if ($request->state == "прекращен"){
                                    //    echo "<td>".$request->date_to."</td>";
                                    //}else{
                                    //    echo "<td>".$request->date_from."</td>";
                                    //}
                                    echo "<td>".$user->ip."</td>";
                                    echo "<td>".$base->shot_name."</td>";
                                    echo "</tr>";
                                }    
                            }
                        }
                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>
