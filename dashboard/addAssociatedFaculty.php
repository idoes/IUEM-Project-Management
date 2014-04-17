<?php
	include_once('dbconnect.php');
	include_once('./php/header.php' );

	function exception_error_handler($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
	
	set_error_handler("exception_error_handler");

		try {
		$first_name = explode(" ",$_GET['facultyName']);
		$first_name = $first_name[0];
		$last_name = explode(" ",$_GET['facultyName']);
		$last_name = $last_name[1];
	} catch (Exception $e) {
		echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>Fatal Error:  Cannot add Faculty with name ".$_GET['facultyName'].".</div>";
		header("Refresh: 3; URL=manageProjects.php");
		die();
	}

	$projectID = $_GET['projectID'];

	$facultyID = mysql_query("SELECT FacultyID from `A_FACULTY` WHERE FirstName='".$first_name."' AND LastName='".$last_name."';", $conn) or die(mysql_error());
	$facultyID = mysql_fetch_assoc($facultyID);	

	$result = mysql_query("INSERT INTO `A_RESEARCH` (FacultyID, ProjectID, ResearchStartDate) VALUES (".$facultyID['FacultyID'].", ".$projectID.", CURDATE());", $conn) or die(mysql_error());

echo <<<EOT
<div class="container-fluid">
	      <div class="row">	        
	        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	          <h1 class="page-header">Success</h1>
	          <h2 class="sub-header">Please wait, redirecting...</h2>
	        </div>
	      </div>
	    </div>
EOT;

header('Refresh: 3; URL=editAssociatedFaculty.php?projectID='.$projectID);
?>
