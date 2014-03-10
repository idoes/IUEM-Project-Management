<?php
/**
 * This file defines database connection. This file is included in any files that needs database connection
 * 
  */

	$conn = mysql_connect("localhost", "root", "toor") or die(mysql_error());
	$select = mysql_select_db("congqi_db", $conn);
	return $conn;

?>
