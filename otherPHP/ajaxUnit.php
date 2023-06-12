<?php
require_once "function.php";

if(!empty($_POST['unitId'])){
    $bd = new BD();
    $unitDAO = new UnitDAO();
    $unit = $unitDAO->getUnitById($bd, $_POST['unitId']);   
    if($unit->id != null){
        $output[] = array(
            'id' => $unit->id,
            'name' => $unit->name
        );
        echo json_encode($output);
    }else{
        echo json_encode("empty");
    }
}

if (!empty($_POST['jobsId'])){
    $bd = new BD();
    $jobsDAO = new JobsDAO();
    $jobs = $jobsDAO->getJobsById($bd, $_POST['jobsId']);
    if($jobs->id != null){
        $output[] = array(
            'id' => $jobs->id,
            'name' => $jobs->name
        );
        echo json_encode($output);
    }else{
        echo json_encode("empty");
    }
}

