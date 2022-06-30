<?php
require_once "function.php";


if(!empty($_POST['baseLocalId'])){
    $bd = new BD();
    $localBaseDAO = new Local_baseDAO();
    $data = $localBaseDAO->getBaseById($bd, $_POST['baseLocalId']);
    if($data != null){
        $output[] = array(
            'id' => $data->id,
            'name' => $data->name,
            'notice' => $data->notice
        );
        echo json_encode($output);        
    }else{
        echo json_encode("empty");
    }
}
