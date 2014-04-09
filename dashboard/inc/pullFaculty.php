<?php
	include_once('../dbconnect.php');
	$q = $_GET['q'];
	$result = mysql_query("SELECT FirstName, LastName from `A_FACULTY` WHERE FirstName LIKE '$q%';", $conn);
	$hint = "";
	
	while($row = mysql_fetch_assoc($result))
	{
		$hint.="<option value=\"".$row['FirstName']." ".$row['LastName']."\">";
	}
	
	echo $hint;
?>