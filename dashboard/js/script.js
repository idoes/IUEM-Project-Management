function init()
{
    $("#startdate").datepicker();
    $("#enddate").datepicker();
    

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