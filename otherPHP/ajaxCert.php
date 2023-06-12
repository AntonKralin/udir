<?php
require_once "function.php";


if(!empty($_POST['certId'])){
    $bd = new BD();
    $certificationDAO = new CertificateDAO();
    $data = $certificationDAO->getCertificateById($bd, $_POST['certId']);
    if($data != null){
        $reason = explode("; ", $data->reason);
        $output[] = array(
            'id' => $data->id,
            'name' => $data->name,
            'number' => $data->number,
            'date_from' => $data->date_from,
            'date_to' => $data->date_to,
            'reason' => $reason,
            'id_user' => $data->id_user,
            'state' => $data->state
        );
        echo json_encode($output);        
    }else{
        echo json_encode("empty");
    }
}