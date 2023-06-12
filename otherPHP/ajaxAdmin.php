<?php
require_once "function.php";

if(!empty($_POST['adminsListId'])){
    $bd = new BD();
    $adminsDAO = new AdminsDAO();
    $admins = $adminsDAO->getAdminById($bd, $_POST['adminsListId']);
    if ($admins->id == null ){
        echo "";
    }else{
        $output[] = array(
            'id' => $admins->id,
            'login' => $admins->login,
            'password' => $admins->password,
            'id_imns' => $admins->id_imns,
            'smtp' => $admins->smtp,
            'id_access' => $admins->id_access
        );
        echo json_encode($output);
    }
}



