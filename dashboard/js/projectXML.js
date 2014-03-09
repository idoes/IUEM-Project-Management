$(document).ready(function(){
        $("#dvContent").append("<ul></ul>");
        $.ajax({
            type: "GET",
            url: "xml/Table-Solo-Project.xml",
            dataType: "xml",
            success: function(xml){
                $(xml).find('ProjectInstance').each(function(){
                var projectID = $(this).find('projectID').text();
                var Title = $(this).find('Title').text();
                var Abstract = $(this).find('Abstract').text();
                var InitialDate = $(this).find('InitialDate').text();
                var LongText = $(this).find('LongText').text();
                var CloseDate = $(this).find('CloseDate').text();
                $("<li></li>").html(projectID + ", " + Title + "," + Abstract + "," + InitialDate + "," + LongText + "," + CloseDate).appendTo("#dvContent ul");
            });
            },
            error: function() {
            alert("An error occurred while processing XML file.");
            }
        });
    });