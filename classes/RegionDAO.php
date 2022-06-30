<?php
require_once 'BD.php';
require_once 'Region.php';

/**
 * Description of RegionDAO
 *
 * @author 301_Kralin_A_V
 */
class RegionDAO {
        
    function getRegionByID($bd, $id){
        $query = 'select * from `region` where `id`="'.$id.'"';
        $data = $bd->query($query);
        if ($data != null){
            $elem = $data[0];
            $region = new Region();
            $region->id = $elem['id'];
            $region->name = $elem['name'];
            return $region;
        }
    }
}
