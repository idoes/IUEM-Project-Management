function init() {

	var direct = getUrlVars()["redirect"];
	//check if page needs redirect set by some database query
	if (direct != undefined) {
		loadPage(direct);
	}
	
	$("#addButton").click(function() {
		if (($('.form-horizontal .control-group').length + 1) > 20) {
			alert("Only 10 more Co-PI are allowed.");
			return false;
		}
		var id = (($('.form-horizontal .control-group').length/2) + 1).toString();
		$('.form-horizontal').append("<div class='form-group control-group'><label for='coInspector"+id+"' class=\"col-sm-2 control-label\">Co PI "+id+" Name</label><div class=\"col-sm-6\"><input autocomplete=\"off\" value=\"\" type=\"text\" list=\"txtHint\" class=\"form-control\" id=\"projectInspector"+id+"\" placeholder=\"Project Inspector "+id+" Name\" name=\"projectInspector"+id+"\" onkeyup=\"showHint(this.value)\"><datalist id=\"txtHint\"></datalist></div></div><div class=\"form-group control-group\"><label for=\"projectInspectorStartDate\" class=\"col-sm-2 control-label\">Co PI "+id+" Start Date</label><div class=\"col-sm-6\"><input value=\"\" type=\"text\" class=\"form-control\" id=\"startDateCOPI"+id+"\" placeholder=\"Project Inspector "+id+" Start Date\" name=\"startDateCOPI"+id+"\"></div></div>");
		createCalendar();
	});

	$("#removeButton").click(function() {
		if ($('.form-horizontal .control-group').length == 0) {
			alert("No more textbox to be removed.");
			return false;
		}

		$(".form-horizontal .control-group:last").remove();
		$(".form-horizontal .control-group:last").remove();
	});
	
	$(document).ready(function() {
    	$('.AAA').dataTable({
	    	"bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bInfo": false,
	        "bAutoWidth": false
    	});
	} );
	
	
	createCalendar();
}

function createCalendar() {
	$("#startdate").datepicker({
		dateFormat : 'yy-mm-dd'
	});
	$("#enddate").datepicker({
		dateFormat : 'yy-mm-dd'
	});
	$("#projectInspectorStartDate").datepicker({
		dateFormat : 'yy-mm-dd'
	});
	
	for(var i = 0; i < 10; i++)
	{
		$('#startDateCOPI'+i).datepicker({
		dateFormat : 'yy-mm-dd'
	});
	}
}

function getUrlVars() {//credit http://papermashup.com/read-url-get-variables-withjavascript/
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
		vars[key] = value;
	});
	return vars;
}

function validateCreateFaculty()
{
	var isOk = true;
	
	if ($("#firstname").val() == "") {
		isOk = false;
		$("#firstname").parent().parent().addClass("has-error");
	}

	if ($("#lastname").val() == "") {
		isOk = false;
		$("#lastname").parent().parent().addClass("has-error");
	}

	if (!validateEmail($("#email").val())) {
		isOk = false;
		$("#email").parent().parent().addClass("has-error");
	}
	
	if(isOk) { $("#post_form").submit(); }

}

function validateEditSingleProject()
{
	var isOk = true;
	if($("#projecttitle").val() == "")
	{
		isOk = false;
		$("#projecttitle").parent().parent().addClass("has-error");
	}
	
	if(!isValidDate($("#startdate").val()))
	{
		isOk = false;
		$("#startdate").parent().parent().addClass("has-error");
	}
	
	if(!$("#enddate").val() == "")
	{
		if(!isValidDate($("#enddate").val()))
		{
			isOk = false;
			$("#enddate").parent().parent().addClass("has-error");
		}
	}
	
	if(!isValidDate($("#projectInspectorStartDate").val()))
	{
		isOk = false;
		$("#projectInspectorStartDate").parent().parent().addClass("has-error");
	}
	
	if(isOk) { $("#post_form").submit(); }
}

function validateEditSingleFaculty()
{
	var isOk = true;
	
	if (!validateEmail($("#username").val())) {
		isOk = false;
		$("#username").parent().parent().addClass("has-error");
	}
	if ($("#userpassword").val() == "") {
		isOk = false;
		$("#userpassword").parent().parent().addClass("has-error");
	}
	if ($("#firstname").val() == "") {
		isOk = false;
		$("#firstname").parent().parent().addClass("has-error");
	}
	if ($("#lastname").val() == "") {
		isOk = false;
		$("#lastname").parent().parent().addClass("has-error");
	}
	//uncomment if need to check
	// if ($("#middlename").val() == "") {
		// isOk = false;
		// $("#middlename").parent().addClass("has-error");
	// }
	// if (!validateEmail($("#email").val())) {
		// isOk = false;
		// $("#email").parent().addClass("has-error");
	// }
	// if ($("#title").val() == "") {
		// isOk = false;
		// $("#title").parent().addClass("has-error");
	// }
	// if ($("#position").val() == "") {
		// isOk = false;
		// $("#position").parent().addClass("has-error");
	// }
	// if ($("#officelocation").val() == "") {
		// isOk = false;
		// $("#officelocation").parent().addClass("has-error");
	// }
// 	
	// if ($("#biotext").val() == "") {
		// isOk = false;
		// $("#biotext").parent().addClass("has-error");
	// }
	// if ($("#biophotolink").val() == "") {
		// isOk = false;
		// $("#biophotolink").parent().addClass("has-error");
	// }
	// if ($("#cvfilelink").val() == "") {
		// isOk = false;
		// $("#cvfilelink").parent().addClass("has-error");
	// }
	
	if(isOk) { $("#post_form").submit(); }

}

function validateCreateAdminUser()
{
	var isOk = true;
	
	if ($("#firstname").val() == "") {
		isOk = false;
		$("#firstname").parent().parent().addClass("has-error");
	}

	if ($("#lastname").val() == "") {
		isOk = false;
		$("#lastname").parent().parent().addClass("has-error");
	}

	if (!validateEmail($("#email").val())) {
		isOk = false;
		$("#email").parent().parent().addClass("has-error");
	}
	
	if(isOk) { $("#post_form").submit(); }
}

function validateEditSingleAdmin()
{
	var isOk = true;
	
	if ($("#firstname").val() == "") {
		isOk = false;
		$("#firstname").parent().parent().addClass("has-error");
	}

	if ($("#userpassword").val() == "") {
		isOk = false;
		$("#userpassword").parent().parent().addClass("has-error");
	}
	
	if ($("#lastname").val() == "") {
		isOk = false;
		$("#lastname").parent().parent().addClass("has-error");
	}

	if (!validateEmail($("#username").val())) {
		isOk = false;
		$("#username").parent().parent().addClass("has-error");
	}
	
	if(isOk) { $("#post_form").submit(); }
}

function validateCreateProject()
{
	var isOk = true;
	if($("#projecttitle").val() == "")
	{
		isOk = false;
		$("#projecttitle").parent().parent().addClass("has-error");
	}
	
	if(!isValidDate($("#startdate").val()))
	{
		isOk = false;
		$("#startdate").parent().parent().addClass("has-error");
	}
	
	if(!$("#enddate").val() == "")
	{
		if(!isValidDate($("#enddate").val()))
		{
			isOk = false;
			$("#enddate").parent().parent().addClass("has-error");
		}
	}
	
	if ($("#projectInspector").val() == "") {
		isOk = false;
		$("#projectInspector").parent().parent().addClass("has-error");
	}
	
	if(!isValidDate($("#projectInspectorStartDate").val()))
	{
		isOk = false;
		$("#projectInspectorStartDate").parent().parent().addClass("has-error");
	}
	
	if(isOk) { $("#post_form").submit(); }
}

function isValidDate(date)
{
    var matches = /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/.exec(date);
    if (matches == null)
    {
    	return false;
    } else {
    	return true;
    }
}

function showHint(str) {
	var xmlhttp;
	if (str.length == 0) {
		document.getElementById("txtHint").innerHTML = "";
		return;
	}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		document.getElementById("txtHint").innerHTML = "";
		xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "inc/pullFaculty.php?q=" + str, true);
	xmlhttp.send();
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
