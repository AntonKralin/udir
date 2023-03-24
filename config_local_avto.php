<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';

$bd = null;
if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
$bd = new BD();
}else{
    exit;
}

$inputs = filter_input_array(INPUT_POST);

$localAvtoBase = null;
if (isset($inputs["id_local_avto_base"])){
    $_SESSION["local_avto_base"] = $inputs["id_local_avto_base"];
    $LocalavtoBase = $inputs["id_local_avto_base"];
}
if (isset($_SESSION["local_avto_base"])){
    $localAvtoBase = $_SESSION["local_avto_base"];
}

$base = null;
$localBaseDAO = new Local_baseDAO();
if ($localAvtoBase != null){
    $base = $localBaseDAO->getBaseById($bd, $localAvtoBase);
}else{
    exit;
}

$localAvtoLineDAO = new Local_avtoLineDAO;
if (isset($inputs["savedate"])){
    $localAvtoLineDAO->deleteLocalAvtoLineBase($bd, $base->id);
    
    if (isset($inputs["name_termination"])){
        $type = $inputs["type_termination"];
        $yourname = $inputs["yourname_termination"];
        $nCheck = $inputs["name_termination"];
        $dCheck = $inputs["date_termination"];
        for($i=0; $i<count($nCheck); $i++){
            $localAvtoLineDAO->insertLocalAvtoLine($bd, $type, $yourname, $nCheck[$i], $dCheck[$i], $base->id);
        }
    }
    
    if (isset($inputs["name_providing"])){
        
        $type = $inputs["type_providing"];
        $yourname = $inputs["yourname_providing"];
        $nCheck = $inputs["name_providing"];
        $dCheck = $inputs["date_providing"];
        for($i=0; $i<count($nCheck); $i++){
            $localAvtoLineDAO->insertLocalAvtoLine($bd, $type, $yourname, $nCheck[$i], $dCheck[$i], $base->id);
        }
    }
    
    if (isset($inputs["name_extention"])){
        
        $type = $inputs["type_extention"];
        $yourname = $inputs["yourname_extention"];
        $nCheck = $inputs["name_extention"];
        $dCheck = $inputs["date_extention"];
        for($i=0; $i<count($nCheck); $i++){
            $localAvtoLineDAO->insertLocalAvtoLine($bd, $type, $yourname, $nCheck[$i], $dCheck[$i], $base->id);
        }
    }
    
    if (isset($inputs["name_unlocking"])){
        $type = $inputs["type_unlocking"];
        $yourname = $inputs["yourname_unlocking"];
        $nCheck = $inputs["name_unlocking"];
        $dCheck = $inputs["date_unlocking"];
        for($i=0; $i<count($nCheck); $i++){
            $localAvtoLineDAO->insertLocalAvtoLine($bd, $type, $yourname, $nCheck[$i], $dCheck[$i], $base->id);
        }
    }
    if (isset($inputs["name_change"])){
        $type = $inputs["type_change"];
        $yourname = $inputs["yourname_change"];
        $nCheck = $inputs["name_change"];
        $dCheck = $inputs["date_change"];
        for($i=0; $i<count($nCheck); $i++){
            $localAvtoLineDAO->insertLocalAvtoLine($bd, $type, $yourname, $nCheck[$i], $dCheck[$i], $base->id);
        }
    }
}

?>
<html>

	<head>
            <title>«Учет доступа к информационным ресурсам»</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="description" content="Учет доступа к информационным ресурсам" />
            <meta name="keywords" content="Учет доступа к информационным ресурсам" />
            <meta name="viewport" content="width=device-width">
            <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
            <link rel="stylesheet" href="styles/main.css?5" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?13"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
	</head>

	<body>
            <form id="form_auto" name="form_auto" autocomplete="off" style="text-align: center" method="post" action="config_local_avto.php">
                
                <div class="avto_div">
		<?php 
                    echo '<H2>'.$base->name.'</H2>';
                    $line = $localAvtoLineDAO->getLocalAvtoLineByBase($bd, $base->id);
                ?>
                </div>
				<br>
                <div class="avto_div">
                    <label>Блок1</label><br>
                    <?php 
                    
                    $buftype = "";
                    $pos=0;
                    $type="";
                    $yourname="";
                    for($i=$pos; $i<count($line); $i++ ){
                        $elem = $line[$i];                        
                        if ($i == $pos){
                            $buftype = $elem->type;
                        }
                        $pos = $i;
                        if (strcmp($buftype,$elem->type)!=0){
                            break;
                        }
                        $type=$elem->type;
                        $yourname=$elem->yourname;
                        echo "<div>";
                        echo "<input type='text' name='name_termination[]' placeholder='Наименование' title='Наименование' value='".$elem->name."' />";
                        echo "<input type='text' name='date_termination[]' placeholder='Данные' title='Данные' style='width:400px' maxlength=100 value='".$elem->date."' />";
                        echo "<button type='button' onclick='removeElement(this)'>-</button>";
                        echo "</div>";

                    }
                    
                    ?>
                    <button type="button" name="termination" onclick="addLine(this);">+</button>
                    <br>
                    <?php 
                        echo "<select name='type_termination' onchange='changeType(this,\"yourname_termination\")'>";
                        foreach ($localState as $elstate){
                            $str = "";
                            if ( $elstate == $type){ 
                                    $str='selected="selected"';
                            }
                            echo "<option value='".$elstate."' ".$str.">".$elstate."</option>";
                        }
                        echo "</select>";

                        echo "<input type='text' id='yourname_termination' name='yourname_termination' placeholder='Название таблицы' title='Название таблицы' value='".$yourname."' />";
                    ?>
                </div>
                <br>
                <div class="avto_div">
                    <label>Блок2</label><br>
                    <?php 
                    $type="";
                    $yourname="";
                    for($i=$pos; $i<count($line); $i++ ){
                        
                        $elem = $line[$i];                        
                        if ($i == $pos){
                            $buftype = $elem->type;
                        }
                        if($pos+1 >= count($line) ){
                            break;
                        }
                        $pos = $i;
                        if (strcmp($buftype,$elem->type)!=0){
                            break;
                        }
                        $type=$elem->type;
                        $yourname=$elem->yourname;
                        echo "<div>";
                        echo "<input type='text' name='name_providing[]' placeholder='Наименование' title='Наименование' value='".$elem->name."' />";
                        echo "<input type='text' name='date_providing[]' placeholder='Данные' title='Данные' style='width:400px' maxlength=100 value='".$elem->date."' />";
                        echo "<button type='button' onclick='removeElement(this)'>-</button>";
                        echo "</div>";
                    }
                    
                    ?>
                    <button type="button" name="providing" onclick="addLine(this);">+</button>
                    <br>
                    <?php 
                        echo "<select name='type_providing' onchange='changeType(this,\"yourname_providing\")'>";
                        foreach ($localState as $elstate){
                            $str = "";
                            if ( $elstate == $type){ 
                                    $str='selected="selected"';
                            }
                            echo "<option value='".$elstate."' ".$str.">".$elstate."</option>";
                        }
                        echo "</select>";

                        echo "<input type='text' id='yourname_providing' name='yourname_providing' placeholder='Название таблицы' title='Название таблицы' value='".$yourname."' />";
                    ?>
                </div>
                <br>
                <div class="avto_div">
                    <label>Блок3</label><br>
                    <?php 
                    $type="";
                    $yourname="";
                    for($i=$pos; $i<count($line); $i++ ){
                        
                        $elem = $line[$i];                        
                        if ($i == $pos){
                            $buftype = $elem->type;
                        }
                        if($pos+1 >= count($line) ){
                            break;
                        }
                        $pos = $i;
                        if (strcmp($buftype,$elem->type)!=0){
                            break;
                        }
                        $type=$elem->type;
                        $yourname=$elem->yourname;
                        echo "<div>";
                        echo "<input type='text' name='name_extention[]' placeholder='Наименование' title='Наименование' value='".$elem->name."' />";
                        echo "<input type='text' name='date_extention[]' placeholder='Данные' title='Данные' style='width:400px' maxlength=100 value='".$elem->date."' />";
                        echo "<button type='button' onclick='removeElement(this)'>-</button>";
                        echo "</div>";
                    }
                    
                    ?>
                    <button type="button" name="extention" onclick="addLine(this);">+</button>
                    <br>
                    <?php 
                        echo "<select name='type_extention' onchange='changeType(this,\"yourname_extention\")'>";
                        foreach ($localState as $elstate){
                            $str = "";
                            if ( $elstate == $type){ 
                                    $str='selected="selected"';
                            }
                            echo "<option value='".$elstate."' ".$str.">".$elstate."</option>";
                        }
                        echo "</select>";

                        echo "<input type='text' id='yourname_extention' name='yourname_extention' placeholder='Название таблицы' title='Название таблицы' value='".$yourname."' />";
                    ?>
                </div>
                <br>
                
                <input type="submit" name="savedate" style="width: 70%" value="Сохранить">
                <br>
                <br>
                <div class="avto_div">
                    <?php 
                        echo "<p>".$locaAvtoNotice."</p>";
                    ?>
                </div>
            </form>
	
	</body>
        <script>
            
            function changeType(val, fild){
                document.getElementById(fild).value = val.value;
            }
            
            function addLine(button){
                let pos = button.parentNode.childElementCount+1;
                let name_button = button.getAttribute("name");
                
                let new_header = document.createElement("div");
                new_header.style = "";
                button.parentNode.insertBefore(new_header, button.parentNode.childNodes[pos]);
                
                let new_name = document.createElement("input");
                new_name.setAttribute("type","text");
                let name = "name_"+name_button+"[]";
                new_name.setAttribute("name",name);
                new_name.setAttribute("placeholder","Наименование");
                new_name.setAttribute("title","Наименование");
                new_name.setAttribute("maxlength","100");
                new_header.insertBefore(new_name, new_header.childNodes[0]);
                
                let new_date = document.createElement("input");
                new_date.setAttribute("type","text");
                let date = "date_"+name_button+"[]";
                new_date.setAttribute("name", date);
                new_date.setAttribute("placeholder","Данные");
                new_date.style="width:400px";
                new_date.setAttribute("title","Данные");
                new_date.setAttribute("maxlength","100");
                new_header.insertBefore(new_date, new_header.childNodes[1]);
                
                let new_remove_Line = document.createElement("button");
                new_remove_Line.setAttribute("type","button");
                new_remove_Line.innerHTML = "-";
                new_remove_Line.onclick = function(){removeElement(new_remove_Line);};
                new_header.insertBefore(new_remove_Line, new_header.childNodes[2]);
                
                let new_br = document.createElement("br");
                new_header.insertBefore(new_br, new_header.childNodes[3]);
                
            }
            
            function removeElement(element){
                element.parentNode.parentNode.removeChild(element.parentNode);
            }
            
        </script>
</html>


