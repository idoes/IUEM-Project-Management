<?php
	include_once('dbconnect.php');
	include_once('inc/utilityFunction.php');
	$data_type = $_GET['data'];
	$list = array();
	
	if($data_type === "admin") {
		//query
		$result = mysql_query("SELECT * FROM A_ADMIN;", $conn) or die(mysql_error());
		
		$i = 0;
		$list[$i] = array('AdminID', 'Email', 'Password', 'FirstAccessDate', 'LastAccessDate', 'FirstName', 'LastName', 'MiddleName');
		
		while($row = mysql_fetch_assoc($result))
		{
			$i++;			
			$list[$i] = array($row['AdminID'], $row['Email'], $row['Password'], $row['FirstAccessDate'], $row['LastAccessDate'], $row['FirstName'], $row['LastName'], $row['MiddleName']);
		}
	} else if($data_type === "faculty") {
		//query
		$result = mysql_query("SELECT * FROM A_FACULTY;", $conn) or die(mysql_error());
		
		$i = 0;
		$list[$i] = array('FacultyID', 'FirstName', 'LastName', 'MiddleName', 'Email', 'Title', 'Position', 'OfficeLocation', 'BioText', 'BioPhotoLink', 'CVFileLink', 'UserName', 'UserPassword', 'FirstAccessDate', 'LastAccessDate');
		
		while($row = mysql_fetch_assoc($result))
		{
			$i++;			
			$list[$i] = array($row['FacultyID'],$row['FirstName'],$row['LastName'],$row['MiddleName'],$row['Email'],$row['Title'],$row['Position'],$row['OfficeLocation'],$row['BioText'],$row['BioPhotoLink'],$row['CVFileLink'],$row['UserName'],$row['UserPassword'],$row['FirstAccessDate'],$row['LastAccessDate']);
		}
	} else if($data_type === "projects") {
		//first, find num of projects.
		//QUERY CURRENT PROJECT
		//each row needs 3 queries
		//query 1 = fetch pi first/lastname
		//query 2 = fetch co-pi first/lastname (place into string tuple)
		//query 3 = fetch faculty first/lastname (place into string tuple)
		$result = mysql_query("SELECT * FROM A_PROJECT;", $conn) or die(mysql_error());
		
		$i = 0;
		$list[$i] = array('ProjectID', 'Title', 'Abstract', 'InitialDate', 'Description', 'CloseDate', 'PI', 'CO-PI', 'Faculty');
		
		while($row = mysql_fetch_assoc($result))
		{
			$i++;
			$query_one = mysql_query("SELECT * FROM A_MANAGEMENT WHERE ProjectID = ".$row['ProjectID']." and Responsibility = 'PI';", $conn) or die(mysql_error());
			
			if(mysql_num_rows($query_one) != 0) {
				$query_one = mysql_fetch_assoc($query_one);
				$query_two = mysql_query("SELECT FirstName, LastName from A_FACULTY WHERE FacultyID = ".$query_one['FacultyID'], $conn) or die(mysql_error());
				$query_two = mysql_fetch_assoc($query_two);
				$list[$i] = array($row['ProjectID'],$row['Title'],$row['Abstract'],$row['InitialDate'],$row['Description'],$row['CloseDate'],$query_two['FirstName']." ".$query_two['LastName'],'','');
				$i++;
			}
			
			$query_one = mysql_query("SELECT * FROM A_MANAGEMENT WHERE ProjectID = ".$row['ProjectID']." and Responsibility = 'CO-PI';", $conn) or die(mysql_error());
			
			if(mysql_num_rows($query_one) != 0) {
				$query_one = mysql_fetch_assoc($query_one);
				$query_two = mysql_query("SELECT FirstName, LastName from A_FACULTY WHERE FacultyID = ".$query_one['FacultyID'], $conn) or die(mysql_error());
				
				while($row_query = mysql_fetch_assoc($query_two))
				{
					$list[$i] = array($row['ProjectID'],$row['Title'],$row['Abstract'],$row['InitialDate'],$row['Description'],$row['CloseDate'],'',$row_query['FirstName']." ".$row_query['LastName'],'');
					$i++;
				}
			}
			
			$query_one = mysql_query("SELECT * FROM A_RESEARCH WHERE ProjectID = ".$row['ProjectID'].";", $conn) or die(mysql_error());
			
			if(mysql_num_rows($query_one) != 0) {
				$query_one = mysql_fetch_assoc($query_one);
				$query_two = mysql_query("SELECT FirstName, LastName from A_FACULTY WHERE FacultyID = ".$query_one['FacultyID'], $conn) or die(mysql_error());
				
				while($row_query = mysql_fetch_assoc($query_two))
				{
					$list[$i] = array($row['ProjectID'],$row['Title'],$row['Abstract'],$row['InitialDate'],$row['Description'],$row['CloseDate'],'','',$row_query['FirstName']." ".$row_query['LastName']);
					$i++;
				}
			}
			
		}
		
		
	}
	$rand = randomCodeGenerator(50);
	$fp = fopen("upload/csv/".$rand.".csv", 'w');
	
	foreach ($list as $fields) {
	    fputcsv($fp, $fields);
	}
	
	fclose($fp);
	header("Location: getFile.php?dir=upload/csv/".$rand.".csv");
?>