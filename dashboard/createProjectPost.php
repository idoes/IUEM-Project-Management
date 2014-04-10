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
					$firstName = explode(" ",$coPINames[$i]);
					$firstName = $firstName[0];
					$lastName = explode(" ",$coPINames[$i]);
					$lastName = $lastName[1];
				} else {
					$interactive_message.="Please only enter CO-PI as 'FirstName LastName' in the textbox!  CO-PI ".$i." will not be added (Don't worry, you may always add them later in edit projects tab)";
					$firstName = "INVALID";$lastName = "INVALID";
					$delayTime = 10;				
				}
				$request = mysql_query("SELECT FacultyID FROM `A_FACULTY`
							WHERE FirstName = '".$firstName."'
							AND LastName = '".$lastName."' LIMIT 1;", $conn) or die(mysql_error());
					
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
			header('Refresh: '.$delayTime.'; URL=dashboard.php');
    	}	
	}
	echo <<<EOT
	   <div class="col-sm-6 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<div class="row bg-danger">
	 		<center><br/>$interactive_message<br/></center>
	 	</div>
	    </div>
EOT;

header("Refresh: 3; url=createProject.php");
?>
