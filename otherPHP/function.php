<?php

spl_autoload_register(function($class){
    $bug = $_SERVER['DOCUMENT_ROOT'].'/udir/classes/'.$class.'.php';
    //echo $bug;
    require_once $bug;
});

function checkDateSQL($date){
    if ($date == null){
        return 'NULL';
    }
    if ($date[4]=='-'){
        $date = "'".$date."'";
    }else{
        $date = 'NULL';
    }
    return $date;
}

function convertDate($date){
    if ($date == null){
        return "";
    }
    $buf = explode("-", $date);
    if (count($buf) == 3){
        $bufdate = $buf[2].".".$buf[1].".".$buf[0];
        return $bufdate;
    }else{
        return $date;
    }
}

function transliteration($word, $case){
$transU = array(
        "А"=>"A", "Б"=>"B", "В"=>"V", "Г"=>"G", "Д"=>"D", "Е"=>"E", "Ё"=>"E", "Ж"=>"ZH", "З"=>"Z", "И"=>"I", "Й"=>"I", 
        "К"=>"K", "Л"=>"L", "М"=>"M", "Н"=>"N", "О"=>"O", "П"=>"P", "Р"=>"R", "С"=>"S", "Т"=>"T", "У"=>"U", "Ф"=>"F", 
        "Х"=>"KH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", "Щ"=>"SHCH", "Ы"=>"Y", "Ь"=>"", "Ъ"=>"IE", "Э"=>"E", "Ю"=>"U", "Я"=>"YA");
$transL = array(
        "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d", "Е"=>"e", "Ё"=>"e", "Ж"=>"zh", "З"=>"z", "И"=>"i", "Й"=>"i", 
        "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n", "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t", "У"=>"u", "Ф"=>"f", 
        "Х"=>"kh", "Ц"=>"ts", "Ч"=>"ch", "Ш"=>"sh", "Щ"=>"shch", "Ы"=>"y", "Ь"=>"", "Ъ"=>"ie", "Э"=>"e", "Ю"=>"u", "Я"=>"ya");	
	
        $word = trim($word);
        $bufword = $word;
        $newword = "";
        if ($case == "upper"){
            $bufword = mb_strtoupper($word);
            $newword = strtr($bufword,$transU);
        }
        if ($case == "lower"){
            $bufword = mb_strtoupper($word);
            $newword = strtr($bufword,$transL);
        }
	
	return $newword;
}

function ipStandart($ip){
    if (isset($ip)){
        $oktet = explode(".", $ip);
        if (count($oktet) == 4){
            $len = strlen($oktet[3]);
            if($len > 3){
                return "000";
            }
            while ($len != 3){
                $oktet[3]= "0".$oktet[3];
                $len = strlen($oktet[3]);
            }
            return $oktet[3];
        }else{
            return "000";
        }
    }else{
        return "000";
    }
}

function getFamily($fio){
    $transFio = transliteration($fio, "upper");
    $buf = explode(" ", $transFio);
    if (count($buf) > 1){
        return $buf[0];
    }else{
        return "";
    }
}

function getRusFalily($fio){
    $buf = explode(" ", $fio);
    if (count($buf) > 1){
        return $buf[0];
    }else{
        return "";
    }
}

function getName($fio){
    $transFio = transliteration($fio, "upper");
    $buf = explode(" ", $transFio);
    if (count($buf) > 2){
        return $buf[1][0];
    }else{
        return "";
    }
}

function getRusName($fio){
    $buf = explode(" ", $fio);
    if (count($buf) > 2){
        return $buf[1];
    }else{
        return "";
    }
}

function getPatronymic($fio){
    $transFio = transliteration($fio, "upper");
    $buf = explode(" ", $transFio);
    if (count($buf) == 3){
        return $buf[2][0];
    }else{
        return "";
    }
}

function getRusPatronymic($fio){
    $buf = explode(" ", $fio);
    if (count($buf) == 3){
        return $buf[2];
    }else{
        return "";
    }
}

function getInitials($fio){
    $name = getName($fio);
    $patronymic = getPatronymic($fio);
    return $name.$patronymic;
}

function getFullInitials($fio){
    $famyli = getFamily($fio);
    $name = getName($fio);
    $patronymic = getPatronymic($fio);
    return $famyli[0].$name.$patronymic;
}
