<?php
require_once "function.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/udir/classes/Local_requestionDAO.php';

if(!empty($_POST['userId'])){
    $bd = new BD();
    $localRequestDAO = new Local_requestionDAO();
    
    $data = $localRequestDAO->getReqestionByUserBase($bd, $_POST['userId'], $_POST['baseId']);
    if($data != null){
        $output[] = array(
            'id' => $data->id,
            'id_user' => $data->id_user,
            'id_local_base' => $data->id_local_base,
            'date_from' => $data->date_from,
            'date_to' => $data->date_to,
            'date_do' => $data->date_do,
            'state' => $data->state,
            'number' => $data->number,
            'notice' => $data->notice
        );
        echo json_encode($output);        
    }else{
        echo json_encode(array());
    }
}else{
    echo json_encode(array());
}