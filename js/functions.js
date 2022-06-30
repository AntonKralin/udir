
//function input_click(){
//	document.location.href = "main.html"
//}

function user_click(){
	//var dialog = document.querySelector('#user_dialog');
	//dialog.showModal();
	$( "#user_dialog" ).dialog( "open" );
}

function check_select(val){
	var elem = document.getElementById('request');
	if (val == 'выгружен на область'){
		elem.style.display="block";
	}else{
		elem.style.display="none";
	}
}

function edit_fild(val){
	//var http = new XMLHttpRequest();
	//var url = 'update_fild.php';
	//var params = 'id_fild='+val;
	//http.open('POST',url, true);
	//http.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	//http.send(params);
	//alert(val);
	document.getElementById('id_fild').value=val;
	document.getElementById('hides').submit();
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

function unloaded_click(){
	//document.location.href = "unloaded.php";
	window.open("unloaded.php", "_blank");
}

function count_click(){
	//document.location.href = "unloaded.php";
	window.open("count.php", "_blank");
}

function unloaded_obl_click(){
	window.open("otchetobl.php", "_blank");
}

function unloaded_mns_click(){
	window.open("otchetmns.php", "_blank");
}

function popup_alert_click(){
	$( "#popup_date" ).dialog( "open" );
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

function fild_click() {
	//var dialog = document.querySelector('#add_fild');
	//dialog.showModal();
	$( "#add_fild" ).dialog( "open" );
}

function check_click() {
	$( "#check" ).dialog( "open" );
}


function update_click(){
	$("#update_user").dialog("open");
}

function update_fild_click(){
	$("#update_fild").dialog("open");
}

function create_click(){
	document.getElementById('hides2').submit();
	//document.getElementById('sub_hides2').onclick();
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
