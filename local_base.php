<?php
session_start();
require_once 'otherPHP/const.php';
require_once 'otherPHP/function.php';
require_once 'otherPHP/PHPExcel.php';

$bd = null;
$base = null;

if(isset($_SESSION['admins'])){
$admins = unserialize($_SESSION['admins']);
$imns   = unserialize($_SESSION['imns']);
$region = unserialize($_SESSION['region']);
$imnsList = unserialize($_SESSION['imnsList']);
$_SESSION["HTTP_REFERER"] = 'main.php';
$select_imns = -1;
if (isset($_SESSION['lbase'])){
    $base = unserialize($_SESSION['lbase']);
}
if ( isset( $_SESSION['select_imns'] ) ){
    $select_imns = $_SESSION['select_imns'];
}
$bd = new BD();
}else{
    exit;
}

$imnsDAO = new ImnsDAO();
$baseDao = new Local_baseDAO();
$localRequestDAO = new Local_requestionDAO();
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
    $base = $baseDao->getBaseById($bd, $inputs['base_click']);
    if ($base){
        $_SESSION["lbase"] = serialize($base);
    }
}

if(isset($inputs['localDialogSubmit'])){
    $id = $inputs["localId"];
    $sUser = $inputs["localUserId"];
    $sBase = $inputs["localBaseId"];
    $fDate = $inputs["localDateFrom"];
    $tDate = $inputs["localDateTo"];
    $dDate = $inputs["localDateDo"];
    $sState = $inputs["localState"];
    $sNumber = $inputs["localNumber"];
    $sNotice = $inputs["localNotice"];
    $sButton = $inputs["localDialogSubmit"];
    
    if ($sButton == "Сохранить"){
        $localRequestDAO->update($bd, $id, $fDate, $tDate, $dDate, $sState, $sNumber, $sNotice);
    } else {
        $localRequestDAO->insert($bd, $sUser, $sBase, $fDate, $tDate, $dDate, $sState, $sNumber, $sNotice);
    }
    
}

if (isset($inputs["localDialogDelete"])){
    $id = $inputs["localId"];
    $localRequestDAO->deleteFild($bd, $id);
}

$usersDAO = new UsersDao();
$baseList = $baseDao->getBaseListByRegion($bd, $region->id);

if (isset($inputs['exportcsv2'])){
    if($base){
        $localRequestDAO = new Local_requestionDAO();
        $localRequestList = null;
        if ($select_imns == 0){
            $localRequestList = $localRequestDAO->getReqestionListByRegionBaseState($bd, $region->id, $base->id, "действующий");
        }else{
            $localRequestList = $localRequestDAO->getReqestionListByImnsBaseState($bd, $imns->id, $base->id, "действующий");
                                    }
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue("A1","ФИО");
        $objPHPExcel->getActiveSheet()->setCellValue("B1","IP");
        $objPHPExcel->getActiveSheet()->setCellValue("C1","Дата предоставления");
        $objPHPExcel->getActiveSheet()->setCellValue("D1","Дата окончания");
        $objPHPExcel->getActiveSheet()->setCellValue("E1","Дата заявки");
        $objPHPExcel->getActiveSheet()->setCellValue("F1","Статус");
        $objPHPExcel->getActiveSheet()->setCellValue("G1","Номер заявки");
        $objPHPExcel->getActiveSheet()->setCellValue("H1","Примечание");
        if($select_imns == 0){
            $objPHPExcel->getActiveSheet()->setCellValue("I1","№");
        }
        
        $i=2;
        foreach ($localRequestList as $requestion){
            $user = $usersDAO->getUsersById($bd, $requestion->id_user);
            $fio = "";
            $ip = "";        
            if ($user){
                $fio = $user->fio;
                $ip = $user->ip;
            }
            $state = $requestion->state;
            
            $objPHPExcel->getActiveSheet()->setCellValue("A".$i,$fio);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$i,$ip);
            $objPHPExcel->getActiveSheet()->setCellValue("C".$i,convertDate($requestion->date_from));
            $objPHPExcel->getActiveSheet()->setCellValue("D".$i,convertDate($requestion->date_to));
            $objPHPExcel->getActiveSheet()->setCellValue("E".$i,convertDate($requestion->date_do));
            $objPHPExcel->getActiveSheet()->setCellValue("F".$i,$state);
            $objPHPExcel->getActiveSheet()->setCellValue("G".$i,$requestion->number);
            $objPHPExcel->getActiveSheet()->setCellValue("H".$i,$requestion->notice);
            if ($select_imns == 0){
                $imnsDAO = new ImnsDAO();
                $imnsBuf = $imnsDAO->getImnsById($bd, $user->id_imns);
                $objPHPExcel->getActiveSheet()->setCellValue("I".$i,$imnsBuf->number);
            }
            $i++;
        }
        
        $filename=$base->name.".csv";
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

if ($baseList != null){
    
    if ($base == null){
        $base = $baseList[0];
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
            <script type="text/javascript" src="js/functions.js?1"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script type="text/javascript" src="js/jquery-ui.min.js"></script>
            <script type="text/javascript" src="js/selectize.min.js"></script>
            <script type="text/javascript" src="js/sort.js"></script>
	</head>
        
        
	<body>
            <div id="top_head">
                <div id="top_left">
                    <?php 
                        
                            echo '<form id="imns_label" method="post" autocomplete="off" action="local_base.php">';
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
                    <!--    <?php echo "<H9>«Учет доступа к информационным ресурсам» ".$region->name."</H9>" ?> -->
					<a href="main.php" class="ahead">«Учет доступа к информационным ресурсам». </a><H9 style="color: white;">Локальные доступы.</H9>					
                </div>
                    
            </div>
            
            <div id="next_top_head">
                <form id="menu_base" method="post" autocomplete="off" action="local_base.php">
                    <select id="base_click" name="base_click" style='font-weight:bold;' onchange="document.getElementById('menu_base').submit();">
                        <?php 
                            foreach ($baseList as $elem ){
                                $val = "";
                                if ($elem->id == $base->id){
                                    $val = "selected";
                                }
                                echo "<option value='".$elem->id."' ".$val.">".$elem->name."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" id="baseSubmit" name="baseSubmit" style="display: none;">
                </form> 
                <div id="top_head_button">
                    <?php 
                        if ( ($admins->id_access == "3") || ($admins->id_access == "4") ){
                            echo '<button id="localButton" onclick="localButtonClick();">Локальные доступы</button>';                    
                        }
                    ?>
                </div>
                <div id="top_right">
                <!--    <div id="top_right_name">
                        <H9>Локальные доступы </H9> &nbsp;
                    </div> -->
                    <form id="export" name="export" style="display: inline;" method="post" action="local_base.php">
                        <input type="text" id="exportcsv2" name="exportcsv2" style="display: none;" value="exportcsv2" />
                        <button class="userButtons" id="exportcsv" name="exportcsv" value="Экпорт CSV">
                            <img class="gwt-Image" style="width: 22px; height: 22px; cursor: pointer;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABxklEQVR42tXXzytEURQH8DeLoSzGRpmlYWM5UposJpsp9lZ+hKXNWPgDaLKwFFZ2mqLUJBu2hCTTLJSy8jMlRWIMb8y4voczPG/eK+/HfZ5Tn824nfPVvHfnXkXxWwkhAtAPq5CFnAX7sAIDELYboA02oABFG57gBhahw06ABP8n1GwLZi2g9Q/is+5hCTqtBuiGA8jDJDRZQOsvxXfdwTLE7QR4hKTF8Ek4Ez/rlr+Odi8CjMKJqK4rSEGN7ABx2AZVF6AM69AiO0A9v4LTMMM2+YHegy6pATQ9aqGOjXMv6tnjSQCDB/MfBMAfgxCCXt5W3Q6Q4940I6jf+xsgBoMwB+cSAlDPeZ4R45kBhRMt8CJtuR1A35tmhmjBFDwbbB4yAwiemVJ46ywL+XUBE9DKvw8085QClIQ3dQRD0MjbMlVJEd7VCxzDGlxXPvQyANUr/8y//VWAqvJ9AJXPeE6odgPQQ5OBMYcy3Ms0gNlrKHsj+noN05yyYocPk24HoJ67ullpWhCGCDSzYTiUEIB6jmjmRAwvLw6P5WZH9byVA4mTi4nZZaXIPRO/CRDlE6zdq5mRAveMyr6cGslyr76PA4jf6h3YwRRCCJ4IEgAAAABJRU5ErkJggg==" title="Экспорт">
                        </button>
                    </form>
                </div>
                    
            </div>             
            
                <?php    
                    if ($base != null){
                        echo '<label_info><font id="blink"> '.$base->notice.' </font></label_info>';
                    }
                ?>
            
                <div id="work">
                    <!-- таблица-->
                    <table border="1" width="100%" cellpadding="1" cols="12" class="sortable" >
                        <thead>
                                <tr>    
                                        <?php 
                                            if ($select_imns == 0){
                                                echo '<td width="5%" >№</td>';
                                            }
                                        ?>
                                        <td width="13%">ФИО</td>
                                        <td width="5%">IP</td>
                                        <td width="5%">Дата предоставления</td>
                                        <td width="5%">Дата окончания</td>
                                        <td width="5%">Дата заявки</td>
                                        <td width="7%">Статус</td>
                                        <td width="7%">Номер заявки</td>
                                        <td >Примечание</td>
                                        <?php 
                                            if ($admins->id_access <= 4){
                                                echo '<td width="1%"> </td>';
                                            }
                                        ?>
                                        
                                </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $localRequestList = null;

                                if ($baseList != null){
    
                                    if ($base == null){
                                        $base = $baseList[0];
                                    }

                                    if ($select_imns == 0){
                                        $localRequestList = $localRequestDAO->getReqestionListByRegionBaseState($bd, $region->id, $base->id, "действующий");
                                    }else{
                                        $localRequestList = $localRequestDAO->getReqestionListByImnsBaseState($bd, $imns->id, $base->id, "действующий");
                                    }

                                    foreach ($localRequestList as $local){
                                        echo "<tr>";
                                        $luser = $usersDAO->getUsersById($bd, $local->id_user);
                                        if ($select_imns == 0){
                                            $bImns = $imnsDAO->getImnsById($bd, $luser->id_imns);
                                            echo "<td>".$bImns->number."</td>";
                                        }

                                        echo "<td>".$luser->fio."</td>";
                                        echo "<td>".$luser->ip."</td>";
                                        echo "<td>".convertDate($local->date_from)."</td>";
                                        echo "<td>".convertDate($local->date_to)."</td>";
                                        echo "<td>".convertDate($local->date_do)."</td>";
                                        echo "<td>".$local->state."</td>";
                                        echo "<td>".$local->number."</td>";
                                        echo "<td>".$local->notice."</td>";
                                        if ($admins->id_access <= 4){
                                            echo '<td><input class="button" type="button" onclick="editLocalRequestion('.$local->id_user.','.$local->id_local_base.')" value="✎" title="Редактировать"></td>';
                                        }
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>    
                </div>
            
            </div>    
                
            <div id="localDialog" style="display:none" title="Локальные доступы">
                <form id="localDialogForm" method="post" action="local_base.php"> 
                    <input type="text" name="localId" id="localId" value="" style="display: none">
                    <select id="localUserId" name="localUserId" >
                        <option value="0"  selected>Выберите пользователя</option>
                        <?php 
                            $activUser = $usersDAO->getUsersActiveList($bd, $imns->id, "0");
                            foreach ($activUser as $user){
                                echo "<option value='".$user->id."'>".$user->fio."</option>";
                            }
                        ?>
                    </select>
                    <br><select id="localBaseId" name="localBaseId">
                        <option value="0">Выберите локальный доступ</option>
                        <?php 
                            foreach ($baseList as $lbase){
                                echo "<option value='".$lbase->id."'>".$lbase->name."</optin>";
                            }
                        ?>
                    </select>
                    <p><input type="text" id="localDateFrom" name="localDateFrom" readonly required value="" title="Дата предоставления" placeholder="Дата предоставления" onclick='choose_data(this);' style="width:480px"/>
                    <p><input type="text" id="localDateTo" name="localDateTo" readonly required value="" title="Дата окончания" placeholder="Дата окончания" onclick='choose_data_to(this);' style="width:480px"/>
                    <p><input type="text" id="localDateDo" name="localDateDo" readonly required value="" title="Дата заявки" placeholder="Дата заявки" onclick='choose_data_do(this);' style="width:480px"/>
                    <br><select id="localState" name="localState">
                        <option value="действующий">действующий</optinon>
                        <option value="прекращен">прекращен</option>
                    </select>
                    <p><input type="text" id="localNumber" name="localNumber" required value="" title="Номер заявки" placeholder="Номер заявки" style="width:480px" autofocus/>
                    <p><input type="text" id="localNotice" name="localNotice" value="" title="Примечание" placeholder="Примечание" style="width:480px"/>
                    <br><input type="submit" id="localDialogSubmit" name="localDialogSubmit" value=Добавить />
                    <?php 
                        if ($admins->id_access <4){
                            echo '<input type="submit" id="localDialogDelete" name="localDialogDelete" value=Удалить />';
                        }
                    ?>
                </form>
            </div>    

            <script>
                let localUserId = $('#localUserId').selectize();
                let localBaseId = $('#localBaseId').selectize();
                $("#localDialog").dialog({
                    autoOpen: false,
                    width: 'auto'
                });
                
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
                
                function choose_data_do(elem){
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

                
                let change = function changeSelect(){
                    let userId = document.getElementById("localUserId").value;
                    let baseId = document.getElementById("localBaseId").value;
                    if( (userId!=="0") && (baseId!=="0")){
                       $.ajax({
                           type: 'POST',
                           url: 'otherPHP/ajaxLocal.php',
                           dataType: 'json',
                           data: 'userId='+userId+'&baseId='+baseId,
                           success: function(json){
                                if ( json !== null ){
                                    document.getElementById('localId').value = json[0].id;
                                    document.getElementById('localDateFrom').value= json[0].date_from;
                                    document.getElementById('localDateTo').value= json[0].date_to;
                                    document.getElementById('localDateDo').value=json[0].date_do;
                                    document.getElementById('localState').value=json[0].state;
                                    document.getElementById('localNumber').value=json[0].number;
                                    document.getElementById('localNotice').value=json[0].notice;
                                    document.getElementById('localDialogSubmit').value='Сохранить';
                                }
                           },
                           error: function(jqXHR, textStatus, errorThrown){
                                console.log(jqXHR.status+textStatus+errorThrown);
                            }
                        });
                    }else{
                        document.getElementById('localDateFrom').value="";
                        document.getElementById('localDateTo').value="";
                        document.getElementById('localDateDo').value="";
                        document.getElementById('localState').value="действующий";
                        document.getElementById('localNumber').value="";
                        document.getElementById('localNotice').value="";
                        document.getElementById('localDialogSubmit').value='Добавить';
                    }
                }
                
                $('#localUserId').on('change', change);
                $('#localBaseId').on('change', change);
                
                function editLocalRequestion(userId, baseId){
                    $( "#localDialog" ).dialog( "open" );
                    let bu = localUserId[0].selectize;
                    bu.setValue(userId);
                    let bu2 = localBaseId[0].selectize;
                    bu2.setValue(baseId);
                }
            </script>
	</body>  
</html>