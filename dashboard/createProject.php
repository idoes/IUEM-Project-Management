<?php
	include_once('./php/header.php');
	include_once('dbconnect.php');
	
	$message_out = "";
	$interactive_message = "";
	$delayTime = 3; //set delay time for refresh, increase this on co-pi error so they can read the dialog.
	if(isset($_POST['projecttitle']))
	{
			
		isset($_POST['projecttitle']) ? $title = trim($_POST['projecttitle']) : null;
		isset($_POST['projectAbstract']) ? $abstract = trim($_POST['projectAbstract']) : null;
		isset($_POST['description']) ? $description = trim($_POST['description']) : null;
		isset($_POST['startdate']) ? $startDate = trim($_POST['startdate']) : null;
		isset($_POST['enddate']) ? $endDate = trim($_POST['enddate']) : null;
		isset($_POST['projectInspector']) ? trim($PI_ID = $_POST['projectInspector']) : null;
		isset($_POST['facultystartdate']) ? trim($facultyStartDate = $_POST['facultystartdate']) : null;
		
		$numCOPI = 0;	
		//Create two arrays to keep track of co-pi names and co-pi start dates
		$coPINames = array();
		$coPIStartDates = array();

		
		//echo print_r($_POST); die();
		for($i = 0; $i < 10; $i++)
		{
			if(isset($_POST['projectInspector'.$i]))
			{
				$numCOPI = $numCOPI + 1;
				$coPINames[$i] = $_POST['projectInspector'.$i];
				$coPIStartDates[$i] = $_POST['startDateCOPI'.$i];
			}
		}

		
				
		//check variable
		(!strlen($title) > 0) ? $interactive_message.="Project title cannot be blank<br/>":null;
		(!strlen($abstract) > 0) ? $interactive_message.="Project abstract cannot be blank<br/>":null;
		(!strlen($description) > 0) ? $interactive_message.="Project description cannot be blank<br/>":null;
		(!strlen($startDate) > 0) ? $interactive_message.="Project start date cannot be blank<br/>":null;
		//(!strlen($endDate) === "") ? $interactive_message.="Project enddate cannot be blank<br/>":null;
		(!strlen($PI_ID) > 0) ? $interactive_message.="Project projectInspector cannot be blank<br/>":null;
		(!strlen($facultyStartDate) > 0) ? $interactive_message.="Faculty startdate cannot be blank<br/>":null;
		
		$pi_first_name = substr($PI_ID , 0, strpos($PI_ID , " "));
		$pi_last_name = substr($PI_ID , strpos($PI_ID , " ") + 1, strlen($PI_ID));
		
		$request = mysql_query("SELECT FacultyID
								FROM A_FACULTY
								WHERE FirstName = '".$pi_first_name."'
								AND LastName = '".$pi_last_name."'", $conn) or die(mysql_error());
								
		$faculty_id_arr = mysql_fetch_array($request);
		$faculty_id = $faculty_id_arr['FacultyID'];
		
		(!strlen($facultyStartDate) > 0) ? $interactive_message.="Project PI start date cannot be blank<br/>":null;
		$success = false;
		if($interactive_message === "")
		{				
			$request = mysql_query("INSERT INTO A_PROJECT
									(Title, Abstract, Initialdate, Description, CloseDate)
									VALUES ('$title','$abstract','$startDate','$description','$endDate');",
									$conn) or die(mysql_error());
									
			$request = mysql_query("SELECT MAX(ProjectID) as theMax
									FROM A_PROJECT", $conn) or die(mysql_error());
									
									
			
			$max = mysql_fetch_assoc($request);
			
			$max = $max['theMax'];
			
			$request = mysql_query("INSERT INTO A_MANAGEMENT
									(FacultyID, ProjectID, ManageStartDate, Responsibility)
									VALUES ($faculty_id, $max, '$facultyStartDate', 'PI')", $conn) or die(mysql_error());
									
			$success = true;
			$interactive_message.="Project Created!<br/>"; 
			
			for($i = 1; $i <= count($coPINames); $i++)
			{		
				//first get the faculty id for this coPI

				if(strpos($coPINames[$i]," ") !== false)
				{
					$firstName = explode(" ",$coPINames[$i])[0];
					$lastName = explode(" ",$coPINames[$i])[1];
				} else {
					$interactive_message.="Please only enter CO-PI as 'FirstName LastName' in the textbox!  CO-PI ".$i." will not be added (Don't worry, you may always add them later in edit projects tab)";
				$firstName = "INVALID";$lastName = "INVALID";
				$delayTime = 10;				
}
				$request = mysql_query("SELECT FacultyID FROM `A_FACULTY`
							WHERE FirstName = '".$firstName."'
							AND LastName = '".$lastName."';", $conn) or 
					
				$facultyNameOut = mysql_fetch_assoc($request);
				

				if(mysql_num_rows($request) == 1)
				{
					$request = mysql_query("INSERT INTO `A_MANAGEMENT`
								(FacultyID, ProjectID, ManageStartDate, Responsibility)
                                                                VALUES (".$facultyNameOut['FacultyID'].", ".$max.", '".$coPIStartDates[$i]."', 'CO-PI');", $conn) or $interactive_message.="Project was created, however CO-PI with name ".$coPINames[$i]." was not added to the project because they could not be found in the database!<br/>";
					if(strpos($interactive_message, $coPINames[$i]) !== false)
					{
						$delayTime = 10;
					}
				}	
		}			


	}
		
		
    	if($success)
    	{
    		$message_out = "Project Created!";
			header('Refresh: '.$delayTime.'; URL=dashboard.php?redirect=create-project');
    	}	
	}
	echo <<<EOT
	   <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	      <h1 class="page-header">$interactive_message</h1>
	    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<form action="createProject.php" class="form-horizontal" role="form" method="post">
				<div class="form-group">
					<label for="projecttitle" class="col-sm-1 control-label">Title:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="projecttitle" placeholder="Project Title" name="projecttitle">
					</div>
				</div>
				<div class="form-group">
					<label for="projecttitle" class="col-sm-1 control-label">Abstract:</label>
					<div class="col-sm-4">
						<textarea rows="6" class="form-control" id="projectAbstrct" placeholder="Project Abstract" name="projectAbstract"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-sm-1 control-label">Description:</label>
					<div class="col-sm-4">
						<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="startdate" class="col-sm-1 control-label">Project Start Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
					</div>
				</div>
				<div class="form-group">
					<label for="enddate" class="col-sm-1 control-label">Project End Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspector" class="col-sm-1 control-label">Project Inspector:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="projectInspector" placeholder="Project Inspector" name="projectInspector">
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspectorStartDate" class="col-sm-1 control-label">project Inspector Start Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="projectInspectorStartDate" placeholder="project Inspector Start Date" name="facultystartdate">
					</div>
				</div>
				<div class="form-group">
					<div class="control-group">
					<label for="projectCoInspector" class="col-sm-1 control-label">Project Co-Inspector:</label>
					<div class="col-sm-4">
						<input value="NULL" type="text" value="NULL" class="form-control" id="projectCoInspector" placeholder="Project Co-Inspector" name="projectCoInspector">
					</div>
					</div>
				</div>
				<!--div class="form-group">
					<div class="col-sm-offset-1 col-sm-4">
				<input type='button' class='btn btn-primary' value='Add Co-PI' id='addButton' />
	    			<input type='button' class='btn btn-primary' value='Remove Co-PI' id='removeButton' />
					</div>
				</div>
				<div class="form-group"><br/>
					<div class="col-sm-offset-1 col-sm-4">
						Note:  Once a project has been created, you can assign team members, upload files, and make changes to the above fields again.<br>
						Issue1: Co-Inspector layout.<br>
						Issue2: Co-Inspector have to have a date for his or her co-inspection on this project.<br>
						Note: we may want to redesign the co-inspector stuff; get those out and follow the below scenario.<br>
						Note: In the perspective of this functionality, I am thinking we can add the default PI only, then add other participatants seperately.<br>
						Note: for the same person, he or she can join a project as a normal reseacher or as a PI or as a Co-PI. <br>
						On the other hands, a person can grant one or more as a normal reseacher or as a PI or as a Co-PI.<br>
						
					</div>
				</div-->
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<br/>
						<button action="editSingleProject.php" type="submit" class="btn btn-primary">
							Save Changes
						</button>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
EOT;
?>
