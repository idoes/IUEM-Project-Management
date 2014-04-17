<?php
	include_once('./php/header.php');
	include_once('dbconnect.php');
	
	$message_out = "";
	$success = false;
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$interactive_message = "";	
			
		isset($_POST['projecttitle']) ? $title = mysql_escape_string(trim($_POST['projecttitle'])) : null;
		isset($_POST['projectAbstract']) ? $abstract = mysql_escape_string(trim($_POST['projectAbstract'])) : $abstract = "";
		isset($_POST['description']) ? $description = mysql_escape_string(trim($_POST['description'])) : $description = "";
		isset($_POST['startdate']) ? $startDate = mysql_escape_string(trim($_POST['startdate'])) : null;
		isset($_POST['enddate']) ? $endDate = mysql_escape_string(trim($_POST['enddate'])) : null;
		isset($_POST['projectInspector']) ? $PI_ID = mysql_escape_string(trim($_POST['projectInspector'])) : null;
		isset($_POST['facultystartdate']) ? $facultyStartDate = mysql_escape_string(trim($_POST['facultystartdate'])) : null;
		
		//check variable
		(!strlen($title) > 0) ? $interactive_message.="Project title cannot be blank<br/>":null;
		//(!strlen($abstract) > 0) ? $interactive_message.="Project abstract cannot be blank<br/>":null;
		//(!strlen($description) > 0) ? $interactive_message.="Project description cannot be blank<br/>":null;
		(!strlen($startDate) > 0) ? $interactive_message.="Project start date cannot be blank<br/>":null;
		(!strlen($PI_ID) > 0) ? $interactive_message.="Project projectInspector cannot be blank<br/>":null;
		(!strlen($facultyStartDate) > 0) ? $interactive_message.="Faculty startdate cannot be blank<br/>":null;
				
		$pi_first_name = substr($PI_ID , 0, strpos($PI_ID , " "));
		$pi_last_name = substr($PI_ID , strpos($PI_ID , " ") + 1, strlen($PI_ID));
		
		$request = mysql_query("SELECT FacultyID
								FROM A_FACULTY
								WHERE FirstName = '".$pi_first_name."'
								AND LastName = '".$pi_last_name."'", $conn) or die(mysql_error());
		
		if(mysql_num_rows($request) == 0) {
			echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>The PI you typed is not present in the faculty database.  Please go back and retry.</div>";
			header("Refresh: 3; URL=editSingleProject.php?projectID=".$_GET['projectID']);
			die();
		}

		$faculty_id_arr = mysql_fetch_array($request);
		$faculty_id = $faculty_id_arr['FacultyID'];
		
		(!strlen($facultyStartDate) > 0) ? $interactive_message.="Project PI start date cannot be blank<br/>":null;
		
		if($interactive_message === "")
		{				
			$request = mysql_query("UPDATE A_PROJECT
									SET Title = '$title',
									Abstract = '$abstract',
									InitialDate = '$startDate',
									Description = '$description',
									CloseDate = '$endDate'
									WHERE ProjectID = ".$_GET['projectID'].";", $conn) or die(mysql_error());
			
			$request = mysql_query("UPDATE A_MANAGEMENT
									SET FacultyID = ".$faculty_id.",
									ManageStartDate = '$facultyStartDate',
									Responsibility = 'PI'	
									WHERE ProjectID = ".$_GET['projectID']."
									AND Responsibility = 'PI';", $conn) or die(mysql_error());
									
			$success = true;
									
		}
		
		
    	if($success)
    	{
    		$message_out = "Project Saved!";
			header('Refresh: 3; URL=manageProjects.php');
    	}	
	}
		
	$projectID = $_GET['projectID'];
	//query DB
	$request = mysql_query("SELECT * FROM A_PROJECT
							WHERE ProjectID = $projectID;", $conn);
	
	$project_result = mysql_fetch_array($request);
	
	$projectTitle = $project_result['Title'];
	$projectAbstract = $project_result['Abstract'];
	$projectDescription = $project_result['Description'];
	$projectStartDate = $project_result['InitialDate'];
	$projectEndDate = $project_result['CloseDate'];
	
	$request = mysql_query("SELECT FacultyID, ManageStartDate, ManageEndDate
							FROM A_MANAGEMENT
							WHERE ProjectID = $projectID
							AND Responsibility = 'PI';") or die(mysql_error());
							
	$faculty_result = mysql_fetch_array($request);
	$faculty_id = $faculty_result['FacultyID'];
	$faculty_start_date = $faculty_result['ManageStartDate'];
	$faculty_end_date = $faculty_result['ManageEndDate'];
	
	$request = mysql_query("SELECT FirstName, LastName
							FROM A_FACULTY
							WHERE FacultyID = $faculty_id");
							
	$name_result = mysql_fetch_array($request);
	$firstLastName = $name_result['FirstName']." ".$name_result['LastName'];
	if($firstLastName === " ")
	{
		$firstLastName = "";
	}

	
	echo <<<EOT
	   <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	      <h1 class="page-header">Edit a Current Project</h1>
	    </div>


EOT;
if($message_out!=="")
{
	echo <<<EOT
			<div class="row">
			
				    <div class="col-sm-6 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				    	<div class="row bg-danger">
	 		<center><br/>$message_out<br/><br/></center>
	 	</div>
	 	</div>
EOT;
} 
echo <<<EOT
	<div class="container-fluid">
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<form id="post_form" action="editSingleProject.php?projectID={$_GET['projectID']}" class="form-horizontal" role="form" method="post">
				<div class="form-group has-warning">
					<label for="projecttitle" class="col-sm-2 control-label">Title:</label>
					<div class="col-sm-6">
						<input value="{$projectTitle}" type="text" class="form-control" id="projecttitle" placeholder="Project Title" name="projecttitle">
					</div>
				</div>
				<div class="form-group">
					<label for="projectAbstract" class="col-sm-2 control-label">Abstract:</label>
					<div class="col-sm-6">
						<textarea rows="6" class="form-control" id="projectAbstrct" placeholder="Project Abstract" name="projectAbstract">{$projectAbstract}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-sm-2 control-label">Description:</label>
					<div class="col-sm-6">
						<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description">{$projectDescription}</textarea>
					</div>
				</div>
				<div class="form-group has-warning">
					<label for="startdate" class="col-sm-2 control-label">Project Start Date:</label>
					<div class="col-sm-6">
						<input value="{$projectStartDate}" type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
					</div>
				</div>
				<div class="form-group">
					<label for="enddate" class="col-sm-2 control-label">Project End Date:</label>
					<div class="col-sm-6">
						<input value="{$projectEndDate}" type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
					</div>
				</div>
				<div class="form-group has-warning">
					<label for="projectInspector" class="col-sm-2 control-label">Project Investigator:</label>
					<div class="col-sm-6">
						<input onkeyup="showHint(this.value)" list="txtHint" value="{$firstLastName}" type="text" class="form-control" id="projectInspector" placeholder="Project Investigator" name="projectInspector">
						<datalist id="txtHint"></datalist>
					</div>
				</div>
				<div class="form-group has-warning">
					<label for="projectInspectorStartDate" class="col-sm-2 control-label">Project Investigator Start Date:</label>
					<div class="col-sm-6">
						<input value="{$faculty_start_date}" type="text" class="form-control" id="projectInspectorStartDate" placeholder="Project Inspector Start Date" name="facultystartdate">
						
					</div>
				</div>
				<!--div class="form-group">
					<div class="col-sm-6">
						<input onclick="window.location='editAssociatedCOPI.php?projectID={$_GET['projectID']}'"type='button' class='btn btn-primary' value='Manage CO-PIs' id='addButton' />
					</div>
				</div-->				
				<div class="form-group">
					<div class="col-sm-6">
						<br/>
						<button type="button" onclick="validateEditSingleProject();" class="btn btn-primary">
							Save Changes
						</button>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
EOT;
include_once('./php/footer.php');
?>
