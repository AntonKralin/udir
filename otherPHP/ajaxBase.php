<?php
require_once "function.php";


if(!empty($_POST['baseId'])){
    $bd = new BD();
    $baseDAO = new BaseDAO();
    $data = $baseDAO->getBaseById($bd, $_POST['baseId']);
    if($data != null){
        $output[] = array(
            'id' => $data->id,
            'name' => $data->name,
            'shot_name' => $data->shot_name,
            'new_login' => $data->new_login,
            'notice' => $data->notice
        );
        echo json_encode($output);        
    }else{
        echo json_encode("empty");
    }
}

