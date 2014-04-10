<?php
	include_once('dbconnect.php');
	include_once('./php/header.php');

	$first_name = explode(" ",$_GET['facultyName']);
	$first_name = $first_name[0];
	$last_name = explode(" ",$_GET['facultyName']);
	$last_name = $last_name[1];

	$projectID = $_GET['projectID'];

	$facultyID = mysql_query("SELECT FacultyID from `A_FACULTY` WHERE FirstName='".$first_name."' AND LastName='".$last_name."';", $conn) or die(mysql_error());
	$facultyID = mysql_fetch_assoc($facultyID);	

	$result = mysql_query("INSERT INTO `A_MANAGEMENT` (FacultyID, ProjectID, ManageStartDate) VALUES (".$facultyID['FacultyID'].", ".$projectID.", CURDATE());", $conn) or die(mysql_error());

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

header('Refresh: 3; URL=editAssociatedCOPI.php?projectID='.$projectID);
?>
