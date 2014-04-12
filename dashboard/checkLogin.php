<?php
	include_once('dbconnect.php');
	session_start();
	session_destroy();
   	session_start();
	/**
	 * LOGIN FORM
	 * 4 session variable will be set on success login
	 * $_SESSION['UID'] = uid relative to table in db.  either FacultyID or AdminID, check access_level variable when doing query to check right table
	 * $_SESSION['EMAIL'] = 'email from db'
	 * $_SESSION['FULL_NAME'] = 'full name from DB'
	 * $_SESSION['ACCESS_LEVEL'] = 'FACULTY' OR 'SUPER_ADMIN'
	 */
	 
		 //get post variable place into variable	 
		isset($_POST['email']) ? $email = $_POST['email'] : Header("Location: ../login.php?message=denied");
		isset($_POST['password']) ? $password = $_POST['password'] : Header("Location: ../login.php?message=denied");
		isset($_POST['user-type']) ? $user_type = $_POST['user-type'] : Header("Location: ../login.php?message=denied"); //faculty | admin
		
		if($user_type === 'faculty')
		{
			$result = mysql_query(" SELECT *
									FROM A_FACULTY
									WHERE UserName = '".$email."'
									AND UserPassword = '".$password."'
									AND IsActive = 'YES'
									LIMIT 1;", $conn) or die(mysql_error());
									
			//$result = mysql_query(" CALL SP_CCC('".$email."','".$password."')", $conn) or die(mysql_error());
			
			$num_result = mysql_num_rows($result);
			$result = mysql_fetch_array($result);
			if ($num_result==1)
			{
				$_SESSION['UID'] = $result['FacultyID']; 	
				$_SESSION['EMAIL'] = $result['Email'];		
				$_SESSION['FULL_NAME'] = $result['FirstName']." ".$result['LastName'];
				$_SESSION['ACCESS_LEVEL'] = "FACULTY";
				
				$checkFirst = mysql_query("SELECT FirstAccessDate FROM `A_FACULTY` WHERE FacultyID = ".$_SESSION['UID'].";", $conn) or die(mysql_error());
				$checkFirst = mysql_fetch_assoc($checkFirst);
				$checkFirst = $checkFirst['FirstAccessDate'];
								
				if($checkFirst === "0000-00-00 00:00:00")
				{
					//update firstAccees/lastaccess
					$updateTimestamp = mysql_query("UPDATE `A_FACULTY` SET FirstAccessDate = NOW() WHERE FacultyID = ".$_SESSION['UID'].";") or die(mysql_error());
				} else {
					//update Lastaccessonly
					$updateTimestamp = mysql_query("UPDATE `A_FACULTY` SET LastAccessDate = NOW() WHERE FacultyID = ".$_SESSION['UID'].";") or die(mysql_error());
				}
				
				header("Location: dashboard.php");
			}
			else 
			{
				header("Location: ../login.php?message=bad-login");
			}	
		}
		else if($user_type === 'admin')
		{
			$result = mysql_query(" SELECT *
									FROM A_ADMIN
									WHERE Email = '".$email."'
									AND Password = '".$password."'
									AND IsActive = 'YES'
									LIMIT 1;", $conn) or die(mysql_error());
									
			//$result = mysql_query(" CALL SP_DDD('".$email."','".$password."')", $conn) or die(mysql_error());						
			
			$num_result = mysql_num_rows($result);
			$result = mysql_fetch_array($result);
			if ($num_result==1)
			{
				$_SESSION['UID'] = $result['AdminID']; 	
				$_SESSION['EMAIL'] = $result['Email'];		
				$_SESSION['FULL_NAME'] = $result['FirstName']." ".$result['LastName'];
				$_SESSION['ACCESS_LEVEL'] = "SUPER_ADMIN";
				
				$checkFirst = mysql_query("SELECT FirstAccessDate FROM `A_ADMIN` WHERE AdminID = ".$_SESSION['UID'].";", $conn) or die(mysql_error());
				$checkFirst = mysql_fetch_assoc($checkFirst);
				$checkFirst = $checkFirst['FirstAccessDate'];
								
				if($checkFirst === "0000-00-00 00:00:00")
				{
					//update firstAccees/lastaccess
					$updateTimestamp = mysql_query("UPDATE `A_ADMIN` SET FirstAccessDate = NOW() WHERE AdminID = ".$_SESSION['UID'].";") or die(mysql_error());
				} else {
					//update Lastaccessonly
					$updateTimestamp = mysql_query("UPDATE `A_ADMIN` SET LastAccessDate = NOW() WHERE AdminID = ".$_SESSION['UID'].";") or die(mysql_error());
				}
				
				header("Location: dashboard.php");
			}
			else 
			{
				header("Location: ../login.php?message=bad-login");
			}
		}
		else 
		{
			echo 'Bad post param.';
			die();
		}
