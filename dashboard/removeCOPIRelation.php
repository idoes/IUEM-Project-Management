<?php
include_once ('dbconnect.php');
include_once ('./php/header.php');

$projectID = $_GET['projectID'];
$facultyID = $_GET['facultyID'];

$result = mysql_query("DELETE FROM `A_MANAGEMENT` WHERE FacultyID = '" . $facultyID . "' AND ProjectID = '" . $projectID . "';", $conn) or die(mysql_error());

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

header('Refresh: 3; URL=editAssociatedCOPI.php?projectID=' . $projectID);
?>
