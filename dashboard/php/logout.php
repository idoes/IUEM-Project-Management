<?php
	session_start();
	session_destroy();
	$_SESSION = array(); //clear session variables
	Header("Location: ../../index.php");
?>