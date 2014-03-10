function init()
{
    $("#startdate").datepicker();
    $("#enddate").datepicker();
    
	var direct = getUrlVars()["redirect"];
	
	//check if page needs redirect set by some database query
	if(direct != "")
	{
		loadPage(direct);
	}

}

function getUrlVars() { //credit http://papermashup.com/read-url-get-variables-withjavascript/
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}


function validateUserPanel()
{
    var goodInput = true;
    if($("#firstname").val().length <= 0)
    {
    	$('.form-group').eq(0).addClass("has-error");goodInput=false;
    }
    
    if($("#lastname").val().length <= 0)
    {
    	$('.form-group').eq(2).addClass("has-error");goodInput=false;
    }
    
    if($("#email").val().length <= 0)
    {
    	$('.form-group').eq(3).addClass("has-error");goodInput=false;
    }
    
    if(goodInput)
    {
    	$('#create-user-post').submit();
    }    
}



function loadPage(_page)
{
	//pass one of the following strings as _page
	//create-user
	//manage-users
	//create-admin
	//manage-admins
	//create-project
	//manage-projects
	//profile
	//help
	
	xmlhttp= new XMLHttpRequest();
	
	if(_page == 'create-user')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=create-user",true);
	} 
	else if(_page == 'manage-users')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=manage-users",true);
	}
	else if(_page == 'create-admin')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=create-admin",true);
	}
	else if(_page == 'manage-admins')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=manage-admins",true);
	}
	else if(_page == 'create-project')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=create-project",true);
	}
	else if(_page == 'manage-projects')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=manage-projects",true);
	}
	else if(_page == 'profile')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=profile",true);
	}
	else if(_page == 'help')
	{
		xmlhttp.open("GET","php/ajax_content.php?ajaxID=help",true);
	}
	
	xmlhttp.send();
	
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("ajax-page-content").innerHTML = xmlhttp.responseText;
			init();
		}
	}

}
