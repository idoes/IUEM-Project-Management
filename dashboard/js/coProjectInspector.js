$(document).ready(function() {

	$("#addButton").click(function() {
		if (($('.form-horizontal .control-group').length + 1) > 10) {
			alert("Only 10 more Co-PI are allowed.");
			return false;
		}
		var id = ($('.form-horizontal .control-group').length + 1).toString();
		$('.form-horizontal').append('<div class="control-group" id="control-group' + id + '"><label class="col-sm-1 control-label" for="projectCoInspector' + id + '">projectCoInspector' + id + '</label><div class="col-sm-4' + id + '"><input type="text" class="form-control" id="projectCoInspector' + id + '" placeholder="Project Co-Inspector"></div></div>');
	});

	$("#removeButton").click(function() {
		if ($('.form-horizontal .control-group').length == 1) {
			alert("No more textbox to be removed.");
			return false;
		}

		$(".form-horizontal .control-group:last").remove();
	});
}); 