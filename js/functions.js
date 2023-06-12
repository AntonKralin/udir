
//function input_click(){
//	document.location.href = "main.html"
//}

function userButtonClick(){
	$( "#userDialog" ).dialog( "open" );
}

function adminButtonClick(){
    $("#adminDialog").dialog("open");
}

function regionButtonClick(){
    $("#regionDialog").dialog("open");
}

function baseButtonClick(){
    $("#baseDialog").dialog("open");
}

function validateUserForm(){
    var valid = true;
    var userJobs = document.getElementById('userJobs').value;
    if(userJobs === ""){
        alert("выберите должность");
        valid = false;
    }
    return valid;
}

function edit_fild(val){
	document.getElementById('id_fild').value=val;
	document.getElementById('hides').submit();
}

function avtoButtonClick(){
    document.getElementById('avto_hides').submit();
}

function localAvtoButtonClick(){
    document.getElementById('local_avto_hides').submit();
}

function recourceButtonClick() {
	$( "#add_fild" ).dialog( "open" );
}

function close_fild_click(){
	window.open("close_fild.php", "_blank");
}

function locButtonClick(){
    window.open("local_base.php", "_blank");
}

function localButtonClick(){
    $( "#localDialog" ).dialog( "open" );
}

function localReportClick(){
    window.open("local_report.php", "_blank");
}

function localReport2Click(){
    window.open("local_report_2.php", "_blank");
}

function JournalReportClick(){
    window.open("journal_user.php", "_blank");
}

function certButtonClick(){
    window.open("cert.php", "_blank");
}

function certDialogOpen(){
    $( "#certDialog" ).dialog( "open" );
}

function popup_alert_click(){
	$( "#popup_date" ).dialog( "open" );
}

function reportsButtonClick(){
        $( '#reportDialog').dialog('open');
}

function check_select(val){
	var elem = document.getElementById('request');
	if (val == 'выгружен на область' || val == 'отправлен в МНС'){
		elem.style.display="block";
	}else{
		elem.style.display="none";
	}
}

function unloaded_obl_click(){
	window.open("otchetobl.php", "_blank");
}

function unloaded_click(){
	//document.location.href = "unloaded.php";
	window.open("unloaded.php", "_blank");
}

function check_click() {
	$( "#check" ).dialog( "open" );
}

function create_click(){
	document.getElementById('hidesotchet').submit();
}

function create_local_click(){
	document.getElementById('hideslocalotchet').submit();
}





//function ok_click_user() {
//	window.location.reload();
//}

//function closed_click(){
//	var dialog = document.querySelector('#user_dialog');
//	dialog.close();
//}

function log_click(){
	window.open("log.php", "_blank");
}

function go_to_main_click(http) {
	//alert(http);
	window.open(http, "_self");
	//history.go(-1);
}

function expired_click(){
	window.open("expired_requestion.php", "_blank");
}

function count_click(){
	//document.location.href = "unloaded.php";
	window.open("count.php", "_blank");
}

//function svod_click(){
//	window.open("svod.php", "_blank");
//}

function admin_click(){
	$( "#administrirovanie" ).dialog( "open" );
}

function otcheti_click(){
	$( "#otcheti" ).dialog( "open" );
}

function close_fild_click(){
	window.open("close_fild.php", "_blank");
}


function update_click(){
	$("#update_user").dialog("open");
}

function update_fild_click(){
	$("#update_fild").dialog("open");
}

//function ok_click_fild() {
//	window.location.reload();
//}

//function closed_click_fild() {
//	var dialog = document.querySelector('#add_fild');
//	dialog.close();
//}

// Link to open the dialog
function show_popup() {
	$( "#dialog" ).dialog( "open" );
	//event.preventDefault();
}
