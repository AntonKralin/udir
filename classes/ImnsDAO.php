<?php
require_once 'Imns.php';
require_once 'BD.php';
/**
 * Description of ImnsDAO
 *
 * @author 301_Kralin_A_V
 */
class ImnsDAO {

    
    function setFild($id, $number, $name, $unp, $address, $post, $id_region, $id_type, $mail, $shot_name, $bik, $score, $bank, $bank_address) {
        $imns = new Imns();
        $imns->id = $id;
        $imns->number = $number;
        $imns->name = $name;
        $imns->unp = $unp;
        $imns->address = $address;
        $imns->post = $post;
        $imns->id_region = $id_region;
        $imns->id_type = $id_type;
        $imns->mail = $mail;
        $imns->shot_name = $shot_name;
        $imns->bik = $bik;
        $imns->score = $score;
        $imns->bank = $bank;
        $imns->bank_address = $bank_address;
        return $imns;
    }
    
    function getImnsById ($bd, $id){
        $query = 'select * from `imns` where `id`="'.$id.'"';
        $data = $bd->query($query);
        if ($data != null){
            $elem = $data[0];
            $imns = new Imns();
            $imns->id = $elem['id'];
            $imns->number = $elem['number'];
            $imns->name = $elem['name'];
            $imns->unp = $elem['unp'];
            $imns->address = $elem['address'];
            $imns->post = $elem['post'];
            $imns->id_region = $elem['id_region'];
            $imns->id_type = $elem['id_type'];
            $imns->mail = $elem['mail'];
            $imns->shot_name = $elem['shot_name'];
            $imns->bik = $elem['bik'];
            $imns->score = $elem['score'];
            $imns->bank = $elem['bank'];
            $imns->bank_address = $elem['bank_address'];
            return $imns;
        }
    }
    
    function updateImns($bd, $imns){
        $query = "UPDATE `imns` SET `number`='".$imns->number."', `name`='".$imns->name."', `unp`='".$imns->unp."', `address`='".$imns->address."', `post`='".$imns->post."', `mail`='".$imns->mail."', `shot_name`='".$imns->shot_name."', `bik`='".$imns->bik."', `score`='".$imns->score."', `bank`='".$imns->bank."', `bank_address`='".$imns->bank_address."'  WHERE `id`=".$imns->id;
        $bd->queryClean($query);
    }
    
    function getImnsList($bd, $imns){
        $result = [];
        if ($imns->id_type == "3"){
            $result[0] = $imns;
            return $result;
        }
        if ($imns->id_type == "2"){
            $query = "select * from `imns` where `id_region` = '".$imns->id_region."'";
            $data = $bd->query($query);
            for($i=0; $i<count($data); $i++){
                $imns1 = new Imns();
                $imns1 = $this->setFild($data[$i]['id'], $data[$i]['number'], $data[$i]['name'], $data[$i]['unp'], $data[$i]['address'], $data[$i]['post'], $data[$i]['id_region'], $data[$i]['id_type'], $data[$i]['mail'], $data[$i]['shot_name'], $data[$i]['bik'], $data[$i]['score'], $data[$i]['bank'], $data[$i]['bank_address']);    
                $result[$i] = $imns1;
            }
            return $result;
        }
        
    }
}
