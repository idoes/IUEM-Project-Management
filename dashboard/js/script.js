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
		$('.form-horizontal').append("<div class='form-group control-group'><label for='coInspector"+id+"' class=\"col-sm-1 control-label\">Co Inspector "+id+" Name</label><div class=\"col-sm-4\"><input autocomplete=\"off\" value=\"\" type=\"text\" list=\"txtHint\" class=\"form-control\" id=\"projectInspector"+id+"\" placeholder=\"Project Inspector "+id+" Name\" name=\"projectInspector"+id+"\" onkeyup=\"showHint(this.value)\"><datalist id=\"txtHint\"></datalist></div></div><div class=\"form-group control-group\"><label for=\"projectInspectorStartDate\" class=\"col-sm-1 control-label\">Co PI "+id+" Start Date</label><div class=\"col-sm-4\"><input value=\"\" type=\"text\" class=\"form-control\" id=\"startDateCOPI"+id+"\" placeholder=\"Project Inspector "+id+" Start Date\" name=\"startDateCOPI"+id+"\"></div></div>");
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

function validateUserPanel() {
	var goodInput = true;
	if ($("#firstname").val().length <= 0) {
		$('.form-group').eq(0).addClass("has-error");
		goodInput = false;
	}

	if ($("#lastname").val().length <= 0) {
		$('.form-group').eq(2).addClass("has-error");
		goodInput = false;
	}

	if ($("#email").val().length <= 0) {
		$('.form-group').eq(3).addClass("has-error");
		goodInput = false;
	}

	if (goodInput) {
		$('#create-user-post').submit();
	}
}

function loadPage(_page) {
	//pass one of the following strings as _page
	//create-user
	//manage-users
	//create-admin
	//manage-admins
	//create-project
	//manage-projects
	//profile
	//help

	xmlhttp = new XMLHttpRequest();

	if (_page == 'create-user') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=create-user", true);
	} else if (_page == 'manage-users') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=manage-users", true);
	} else if (_page == 'create-admin') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=create-admin", true);
	} else if (_page == 'manage-admins') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=manage-admins", true);
	} else if (_page == 'create-project') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=create-project", true);
	} else if (_page == 'manage-projects') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=manage-projects", true);
	} else if (_page == 'profile') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=profile", true);
	} else if (_page == 'help') {
		xmlhttp.open("GET", "php/ajax_content.php?ajaxID=help", true);
	}

	xmlhttp.send();

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("ajax-page-content").innerHTML = xmlhttp.responseText;
			createCalendar();
			init();
		}
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
