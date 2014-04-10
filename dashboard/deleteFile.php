<?php
	include_once('dbconnect.php');
	include_once('./php/header.php');
	
	$attachmentID = $_GET['attachmentID'];
	
	
	$attachmentRequest = mysql_query("SELECT ProjectID
										FROM `A_ATTACHMENT`
										WHERE AttachmentID = ".$attachmentID.";", $conn);
	$thisObejct = mysql_fetch_assoc($attachmentRequest);
	
	$projectID = $thisObejct['ProjectID']; 
	
	


	$result = mysql_query("DELETE FROM `A_ATTACHMENT` WHERE AttachmentID = ".$attachmentID.";", $conn) or die(mysql_error());

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

header('Refresh: 3; URL=fileUpload.php?projectID='.$projectID);
?>