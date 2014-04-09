<?php
	include_once('dbconnect.php');
	$code = $_GET['theQueryString'];
	
	//do db update
	
	$result =  "UPDATE A_FACULTY
				SET IsActive = 'YES'
				WHERE ActivationCode = '$code';";
				
	$result = mysql_query($result, $conn) or die (mysql_error());
	
		$result =  "UPDATE A_ADMIN
				SET IsActive = 'YES'
				WHERE ActivationCode = '$code';";
	
	$result = mysql_query($result, $conn) or die (mysql_error());
	
	echo "Your account has been activated!  Redirecting in 3 seconds...";
	
	header('Refresh: 3; URL=dashboard.php');

?>