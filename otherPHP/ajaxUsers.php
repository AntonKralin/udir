<?php
require_once "function.php";

if(!empty($_POST['userId'])){
    $bd = new BD();
    $usersDAO = new UsersDao();
    $user = $usersDAO->getUsersById($bd, $_POST['userId'] );
    if ($user->id != null){
        $output[] = array(
            'id' => $user->id,
            'fio' => $user->fio,
            'ip' => $user->ip,
            'telefon' => $user->telefon,
            'id_jobs' => $user->id_jobs,
            'id_unit' => $user->id_unit,
            'active' => $user->active    
        );
        echo json_encode($output);
    } else {
        echo json_encode("empty");
    }
}