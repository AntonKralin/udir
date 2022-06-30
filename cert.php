<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';
require_once 'otherPHP/PHPExcel.php';

$bd = null;

if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
$_SESSION["HTTP_REFERER"] = 'main.php';
$select_imns = $admins->id_imns;
if ( isset( $_SESSION['select_imns'] ) ){
    $select_imns = $_SESSION['select_imns'];
}

$select_cert = "ALL";
$certList = null;
if (isset( $_SESSION['select_cert'])){
    $select_cert = $_SESSION['select_cert'];
}

$bd = new BD();
}else{
    exit;
}

$certificationDAO = new CertificateDAO();
$imnsDAO = new ImnsDAO();
$inputs = filter_input_array(INPUT_POST);



$selectId = $imns->id;
if(isset($inputs["imns_list"])){
    $selectId = $inputs["imns_list"];
    $_SESSION['select_imns']=$selectId;
    $select_imns = $selectId;
    $imnsDAO = new ImnsDAO();
    if($selectId == "0"){
        $imns = $imnsDAO->getImnsById($bd, $admins->id_imns);
    }else{
        $imns = $imnsDAO->getImnsById($bd, $selectId);
    }
    $_SESSION["imns"] = serialize($imns);
}

if(isset($inputs["base_click"])){  
    $select_cert = $inputs["base_click"];
    $_SESSION['select_cert']= $select_cert;
}

if(isset($inputs['certDialogSubmit'])){
    $certId = $inputs['certId'];
    $certNameID = $inputs['certName'];
    $certDateFrom = $inputs['certDateFrom'];
    $certDateTo = $inputs['certDateTo'];
    $certReason = "";
    foreach ($inputs['certReason'] as $el){
        $certReason .= $el."; ";
    }
    $certReason = substr($certReason, 0, -2);
    $certStateID = $inputs['certState'];
    $certUser = $inputs['certUser'];
    $certNumber = $inputs['certNumber'];
    
    if ($certId == "0"){
        $certificationDAO->insert($bd, $certName[$certNameID], $certDateFrom, $certDateTo, $certReason, $certState[$certStateID], $certUser, $certNumber);
    }else{
        $certificationDAO->update($bd, $certId, $certName[$certNameID], $certDateFrom, $certDateTo, $certReason, $certState[$certStateID], $certNumber);
    }
    
}

if (isset($inputs['certDelete'])){
    $certId = $inputs['certId'];
    if ($certId != "0"){
        $certificationDAO->delete($bd, $certId);
    }
}

$usersDAO = new UsersDao();

if(isset($select_cert)){   
    
    if ($select_imns == 0){
        if ($select_cert == "ALL"){
            $certList = $certificationDAO->getCertificateByRegionState($bd, $region->id,"действующий");
        }else{
            $certList = $certificationDAO->getCertificateByRegionNameState($bd, $region->id, $select_cert,"действующий");
        }
    }else{
        if ($select_cert == "ALL"){
            $certList = $certificationDAO->getCertificateByImnsState($bd, $select_imns,"действующий");
        }else{
            $certList = $certificationDAO->getCertificateByImnsNameState($bd, $select_imns, $select_cert,"действующий");
        }
    }

}else{
    if ($select_imns == 0){
        $certList = $certificationDAO->getCertificateByRegionState($bd, $region->id,"действующий");
    }else{
        $certList = $certificationDAO->getCertificateByImnsState($bd, $select_imns,"действующий");
    }
}

if (isset($inputs['exportcsv2'])){
    if($certList){
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue("A1","ФИО");
        $objPHPExcel->getActiveSheet()->setCellValue("B1","Выдан");
        $objPHPExcel->getActiveSheet()->setCellValue("C1","Номер");
        $objPHPExcel->getActiveSheet()->setCellValue("D1","С");
        $objPHPExcel->getActiveSheet()->setCellValue("E1","По");
        $objPHPExcel->getActiveSheet()->setCellValue("F1","Статус");
        $objPHPExcel->getActiveSheet()->setCellValue("G1","Основание");
        if($select_imns == 0){
            $objPHPExcel->getActiveSheet()->setCellValue("H1","№");
        }
        
        $i=2;
        foreach ($certList as $cert){
            $user = $usersDAO->getUsersById($bd, $cert->id_user);
            
            $objPHPExcel->getActiveSheet()->setCellValue("A".$i,$user->fio);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$i,$cert->name);
            $objPHPExcel->getActiveSheet()->setCellValue("C".$i,$cert->number);
            $objPHPExcel->getActiveSheet()->setCellValue("D".$i,convertDate($cert->date_from));
            $objPHPExcel->getActiveSheet()->setCellValue("E".$i,convertDate($cert->date_to));
            $objPHPExcel->getActiveSheet()->setCellValue("F".$i,$cert->state);
            $objPHPExcel->getActiveSheet()->setCellValue("G".$i,$cert->reason);
            if ($select_imns == 0){
                $imnsDAO = new ImnsDAO();
                $imnsBuf = $imnsDAO->getImnsById($bd, $user->id_imns);
                $objPHPExcel->getActiveSheet()->setCellValue("H".$i,$imnsBuf->number);
            }
            $i++;
        }

        $filename=$select_cert.".csv";
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-control: max-age=0');
        echo "\xEF\xBB\xBF";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV')->setDelimiter(";");
        $objWriter->save('php://output');
        exit;
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
            <link rel="stylesheet" href="styles/main.css?14" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.structure.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/jquery-ui.theme.min.css" type="text/css" />
            <link rel="stylesheet" href="styles/selectize.default.css" type="text/css" />
            <script type="text/javascript" src="js/functions.js?5"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
            <script type="text/javascript" src="js/sort.js?1"></script>
	</head>
        
        
	<body>
            <div id="top_head">
                <div id="top_left">
                    <?php 
                        
                            echo '<form id="imns_label" method="post" autocomplete="off" action="cert.php">';
                            echo '<select id="imns_list" name="imns_list" onchange="document.getElementById(\'imns_label\').submit();">';
                            if ($admins->id_access <= 3 ){
                                echo "<option value='0'>All</option>";
                            }
                            foreach ($imnsList as $elem){
                                $val = "";
                                if ($elem->id == $select_imns){
                                    $val = "selected";
                                }
                                echo "<option value='".$elem->id."' ".$val.">".$elem->name."</option>";
                            }
                            echo '</select>';
                            echo '<input type="submit" id="imnsSubmit" name="imnsSubmit" style="display: none;">';
                            echo '</form>';
                        
                    ?>

                </div>
                
                <div id="top_center">
                <!--    <?php echo "<H9>«Учет доступа к информационным ресурсам». ".$region->name.".</H9>" ?>  -->
				<a href="main.php" class="ahead">«Учет доступа к информационным ресурсам». </a><H9 style="color: white;">Сертификаты.</H9>
                </div>
                    
            </div>
            
            <div id="next_top_head">
                <form id="menu_base" method="post" autocomplete="off" action="cert.php">
                    <select id="base_click" name="base_click" style='font-weight:bold;' onchange="document.getElementById('menu_base').submit();">
                        <option value="ALL">ALL</option>
                        <?php 
                            foreach ($certName as $elem ){
                                $val = "";
                                if ($elem == $select_cert){
                                    $val = "selected";
                                }
                                echo "<option value='".$elem."' ".$val.">".$elem."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" id="baseSubmit" name="baseSubmit" style="display: none;">
                </form> 
                <div id="top_head_button">
                     <?php 

                        if ( ($admins->id_access == "3") || ($admins->id_access == "4") ){
                            echo '<input type="button"  onclick="certDialogOpen();" value="Сертификаты" title="Сертификаты" />';   
                        }
                    ?>
                </div> 
                <div id="top_right">
                <!--    <div id="top_right_name">
                        <H9>Сертификаты </H9> &nbsp;
                    </div> -->
                    <form id="export" name="export" style="display: inline;" method="post" action="cert.php">
                        <input type="text" id="exportcsv2" name="exportcsv2" style="display: none;" value="exportcsv2" />
                        <button class="userButtons" id="exportcsv" name="exportcsv" value="Экпорт CSV">
                            <img class="gwt-Image" style="width: 22px; height: 22px; cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABxklEQVR42tXXzytEURQH8DeLoSzGRpmlYWM5UposJpsp9lZ+hKXNWPgDaLKwFFZ2mqLUJBu2hCTTLJSy8jMlRWIMb8y4voczPG/eK+/HfZ5Tn824nfPVvHfnXkXxWwkhAtAPq5CFnAX7sAIDELYboA02oABFG57gBhahw06ABP8n1GwLZi2g9Q/is+5hCTqtBuiGA8jDJDRZQOsvxXfdwTLE7QR4hKTF8Ek4Ez/rlr+Odi8CjMKJqK4rSEGN7ABx2AZVF6AM69AiO0A9v4LTMMM2+YHegy6pATQ9aqGOjXMv6tnjSQCDB/MfBMAfgxCCXt5W3Q6Q4940I6jf+xsgBoMwB+cSAlDPeZ4R45kBhRMt8CJtuR1A35tmhmjBFDwbbB4yAwiemVJ46ywL+XUBE9DKvw8085QClIQ3dQRD0MjbMlVJEd7VCxzDGlxXPvQyANUr/8y//VWAqvJ9AJXPeE6odgPQQ5OBMYcy3Ms0gNlrKHsj+noN05yyYocPk24HoJ67ullpWhCGCDSzYTiUEIB6jmjmRAwvLw6P5WZH9byVA4mTi4nZZaXIPRO/CRDlE6zdq5mRAveMyr6cGslyr76PA4jf6h3YwRRCCJ4IEgAAAABJRU5ErkJggg==" title="Экспорт">
                        </button>
                    </form>
                </div>
            </div>   
            
                
            <form method="post" autocomplete="off" action="cert.php">  
                    <div id="work_cert">
                        <!-- таблица-->
                        <table  border="1" width="100%" cellpadding="1" cols="9" class="sortable" >
                            <thead>
                                    <tr>    
                                            <td windth="2%">ИМНС</td>
                                            <td width="13%" >ФИО</td>
                                            <td width="13%">Подразделение</td>
                                            <td width="13%">Должность</td>
                                            <td width="7%">Выдан</td>
                                            <td width="10%">Номер</td>
                                            <td width="6%">С</td>
                                            <td width="6%">По</td>
                                            <td width="7%">Статус</td>
                                            <td>Основание</td>
                                            <?php 
                                                if ($admins->id_access <= 4){
                                                    echo '<td width="2%"> </td>';
                                                }
                                            ?>
                                            
                                    </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    const _EXPIRE_DAY = "+2 month";
                                    $tdata = date("d.m.Y",strtotime(_EXPIRE_DAY));
                                    $ndata = date("d.m.Y");
                                    $d1 = strtotime($tdata);
                                    
                                    $d3 = strtotime($ndata);
                                   
                                    foreach ($certList as $cert){
                                        $user = $usersDAO->getUsersById($bd, $cert->id_user);
                                        $certImns = $imnsDAO->getImnsById($bd, $user->id_imns);
                                        $jobsDAO = new JobsDAO();
                                        $unitDAO = new UnitDAO();
                                        
                                        $name = "";
                                        $unit = "";
                                        if ($user){
                                            $job = $jobsDAO->getJobsById($bd, $user->id_jobs);
                                            $name = $job->name;
                                            $unit_obj = $unitDAO->getUnitById($bd, $user->id_unit);
                                            $unit = $unit_obj->name;
                                        }
                                        
                                        echo "<tr>";
                                        $col = "";
                                        $d2 = strtotime($cert->date_to);
                                        if (($d1>$d2) && ($d2>=$d3)){
                                                $col = " style='color:orange;' ";
                                            }
                                            if ($d3>$d2){
                                                 $col = " style='color:red;' ";
                                            }
                                        
                                        echo "<td>".$certImns->number."</td>";
                                        echo "<td>".$user->fio."</td>";
                                        echo "<td>".$unit."</td>";
                                        echo "<td>".$name."</td>";
                                        echo "<td ".$col." >".$cert->name."</td>";
                                        echo "<td ".$col." >".$cert->number."</td>";
                                        echo "<td ".$col." >".convertDate($cert->date_from)."</td>";             
                                        echo "<td ".$col." >".convertDate($cert->date_to)."</td>";
                                        echo "<td ".$col." >".$cert->state."</td>";
                                        echo "<td ".$col." >".$cert->reason."</td>";
                                        if ($admins->id_access <= 4){
                                            echo '<td><input class="button" type="button" onclick="editCert('.$cert->id.');" value="✎" title="Редактировать"></td>';
                                        }
                                        echo "</tr>";
                                        
                                    }
                                    
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            
            </form>           
            
                <div id="certDialog" style="display:none" title="Сертификаты">
                    <form id="certDialogForm" method="post" action="cert.php">
                        <select id="certId" name="certId">
                            <option value="0"  selected>Выберите сертификат</option>
                            <?php 
                                foreach ($certList as $cert){
                                    $user = $usersDAO->getUsersById($bd, $cert->id_user);
                                    $str = $user->fio." ".$cert->name." ".$cert->date_from." ".$cert->state;
                                    echo "<option value='".$cert->id."'>".$str."</option>";
                                }
                            ?>
                        </select>
                        <select id="certUser" name="certUser" >
                        <option value="0"  selected>Выберите пользователя</option>
                        <?php 
                            $activUser = $usersDAO->getUsersActiveList($bd, $imns->id, "0");
                            foreach ($activUser as $user){
                                echo "<option value='".$user->id."'>".$user->fio."</option>";
                            }
                        ?>
                        </select>
                        <?php 
                            echo "<br><select id='certName' name='certName'>";
                            for ($i=0; $i<count($certName); $i++){
                                echo "<option value='".$i."'>".$certName[$i]."</option>";
                            }
                            echo "</select>";
                        ?>
                        <p><input type="text" id="certDateFrom" name="certDateFrom" readonly required value="" title="Дата предоставления" placeholder="Дата предоставления" onclick='choose_data(this);' style="width:480px"/>
                        <p><input type="text" id="certDateTo" name="certDateTo" readonly required value="" title="Дата окончания" placeholder="Дата окончания" onclick='choose_data_to(this);' style="width:480px"/>
                        <?php 
                            echo "<br><select id='certState' name='certState'>";
                            for ($i=0; $i<count($certState); $i++){
                                echo "<option value='".$i."'>".$certState[$i]."</option>";
                            }
                            echo "</select>";
                        ?>
                        
                        <p><input type="text" id="certNumber" name="certNumber" value="" title="Номер" placeholder="Номер" style="width:480px" autofocus/>
                        <p>
                            <select id="certReason" name="certReason[]" multiple>
                                <option value="" disabled>Основания для предоставления</option>
                                <?php 
                                    $act = "";
                                    foreach($sertBase as $reason){
                                        echo "<option value='".$reason."'>".$reason."</option>";
                                    }
                                ?>
                            </select>
                        </p>
                        <button type="submit" id="certDialogSubmit" name="certDialogSubmit">Добавить</button>
                        <?php
                            if ($admins->id_access <4){
                                echo '<button type="submit" id="certDelete" name="certDelete">Удалить</button>';
                            }
                        ?>
                          
                        <button type="button" onclick="TwoYearsAdd()">+2 года</button>
                    </form>
                </div>
            <script>
                let certUser = $('#certUser').selectize();
                let certId = $('#certId').selectize();
                
                $("#certDialog").dialog({
                    autoOpen: false,
                    width: 'auto'
                });
                
                function TwoYearsAdd(){
                    var startData = document.getElementById('certDateFrom');
                    var closeData = document.getElementById('certDateTo');
                    
                    if (startData.value.length === 10){
                        let year = startData.value.slice(0,4);
                        
                        year = Number(year)+2;
                        startData.value=year+startData.value.slice(4);
                    }
                    
                    if (closeData.value.length === 10){
                        let year = closeData.value.slice(0,4);
                        year = Number(year)+2;
                        closeData.value=year+closeData.value.slice(4);
                    }
                    
                }
                
                function choose_data(elem){
			//$.datepicker.setDefaults($.datepicker.regional['ru']);
			$(elem).datepicker({
				changeYear: true,
				dateFormat: "yy-mm-dd",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                                firstDay: 1
			});	
			$(elem).datepicker("show");
		}
		
		function choose_data_to(elem){
			$(elem).datepicker({
				showButtonPanel: true,
				beforeShow: function( input ){
					setTimeout( function(){
						var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
						$("<button>",{
							text: "Clear",
							click: function(){
								$.datepicker._clearDate(input);
							}
						}).appendTo(buttonPane).addClass("uit-datepicker-clear ui-state-default ui-priority-prime ui-corner-all");
					},1);
				},
				changeYear: true,
				dateFormat: "yy-mm-dd",
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                                firstDay: 1
			});	
			$(elem).datepicker("show");
		}                
                
                $('#certId').on('change', function(){
                    var certId = $(this).val();
                    if (certId != 0){
                        $.ajax({
                           type: 'POST',
                           url: 'otherPHP/ajaxCert.php',
                           dataType: 'json',
                           data: 'certId='+certId,
                           success: function(json){
                                let certState = new Map(); 
                                certState.set("действующий", 0);
                                certState.set( "прекращен",1);
                                let certName = new Map();
                                certName.set ("ГосСУОК", 0);
                                certName.set ("МНС",1);
                                //console.log(json[0]);
                                document.getElementById('certName').value=certName.get(json[0].name);
                                document.getElementById('certNumber').value = json[0].number;
                                document.getElementById('certDateFrom').value=json[0].date_from;
                                document.getElementById('certDateTo').value=json[0].date_to;
                                //document.getElementById('certReason').value=json[0].reason;
                                var sReason = document.getElementById('certReason');
                                for (let optio in sReason.options){
                                    for (let value in json[0].reason){
                                        if (value === optio.value ){
                                            //console.log(value);
                                            optio.selected = true;
                                        }
                                    }
                                }
                                document.getElementById('certState').value=certState.get(json[0].state);
                                let control = certUser[0].selectize;
                                control.setValue(json[0].id_user);
                                document.getElementById('certDialogSubmit').innerHTML='Сохранить';
                                document.getElementById('certDelete').style.display='inline';
                           },
                           error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('certName').value="0";
                        document.getElementById('certNumber').value = "";
                        document.getElementById('certDateFrom').value="";
                        document.getElementById('certDateTo').value="";
                        var sReason = document.getElementById('certReason');
                                for (let optio in sReason.options){
                                    optio.selected = false;
                                }
                        document.getElementById('certState').value="0";
                        document.getElementById('certUser').value="0";
                        document.getElementById('certDialogSubmit').innerHTML='Добавить';
                        document.getElementById('certDelete').style.display='none';
                    }
                });
                
                function editCert(id){
                    $( "#certDialog" ).dialog( "open" );
                    let bu = certId[0].selectize;
                    bu.setValue(id);
                    var sReason = document.getElementById('certReason');
                                for (let optio in sReason.options){
                                    optio.selected = false;
                                }
                }
                
            </script>
	</body>  
</html>



