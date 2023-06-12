<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
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
            <link rel="stylesheet" href="styles/main.css?11" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?5"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/sort.js"></script>
	</head>

	<body>
            <div id="work_all">
                <table border="1" width="100%" cellpadding="1" class="sortable" >
                <thead>
                    <tr>
                        <?php 
                              $localBaseDAO = new Local_baseDAO();
                              $baseList = $localBaseDAO->getBaseListByRegion($bd, $region->id);
                              
                              echo "<td rowspan='2' style='height: 100px;'> </td>";
                              echo "<td  rowspan='2' style='height: 100px;'>Статус</td>";
                              foreach ($baseList as $base){
                                  if ( $base->archive != 0){
                                    continue;
                                  }
                                  echo "<td colspan='2'>".$base->name."</td>";
                              }
                              
                        ?>
                    </tr>
                    <tr>
                        <?php 
                        foreach ($baseList as $base){
                            if ( $base->archive != 0){
                                continue;
                            }
                            echo "<td><p class='vertical' style='height: 100px;'>Дата присвоения</p></td>";
                            echo "<td><p class='vertical' style='height: 100px;'>Дата прекращения</p></td>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $userDAO = new UsersDao();
                        $localRequestionDAO = new Local_requestionDAO();
                        $userList = $userDAO->getUsersActiveList($bd, $imns->id, "0");
                        
                        foreach( $userList as $user){
                            echo "<tr>";
                            echo "<td>".$user->fio."</td>";
                            $ustate = "Действующий";
                            if ( $user->active != 0){
                                $ustate = "Прекращен";
                            }
                            echo "<td>".$ustate."</td>";
                            
                            foreach ($baseList as $base){
                                if ( $base->archive != 0 ){
                                    continue;
                                }
                                $rez = $localRequestionDAO->getReqestionByUserBase($bd, $user->id, $base->id);
                                if ($rez != null){
                                    echo "<td>".$rez->date_from."</td>";
                                    echo "<td>".$rez->date_to."</td>";
                                }else{
                                    echo "<td>-</td>";
                                    echo "<td>-</td>";
                                }
                            }
                            
                            echo "</tr>";
                        }
                        
                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>

