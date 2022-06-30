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
                        <?php 
                            $baseDAO = new BaseDAO();
                            $baseList = $baseDAO->getBaseList($bd, $region->id);
                            
                            echo "<td></td>";
                            foreach ($baseList as $elem){
                                echo "<td>".$elem->shot_name."</td>";
                            }
                            
                        ?>
                    </tr>
                </thead>
                <tbody class="table_body">	

                    <?php 
                        $requestionDAO = new RequestionDAO();  
                        
                        foreach ($imnsList as $elem){
                            
                            echo '<tr><td>'.$elem->number.'</td>';
                            foreach ($baseList as $base){
                                $count = $requestionDAO->getActiveCount($bd, $base->id, $elem->id);
                                echo '<td>'.$count.'</td>';
                            }
                            echo '</tr>';
                        }

                    ?>

                </tbody>
                </table>
            </div>
	
	</body>
	
</html>

