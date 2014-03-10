<?php
	include_once('dbconnect.php');
	if (session_status() == PHP_SESSION_NONE) 
	{
    	session_start();
	}
	/**
	 * LOGIN FORM
	 * 4 session variable will be set on success login
	 * $_SESSION['UID'] = uid relative to table in db.  either FacultyID or AdminID, check access_level variable when doing query to check right table
	 * $_SESSION['EMAIL'] = 'email from db'
	 * $_SESSION['FULL_NAME'] = 'full name from DB'
	 * $_SESSION['ACCESS_LEVEL'] = 'FACULTY' OR 'SUPER_ADMIN'
	 */
	 
	 //check if user already logged in
	 if(!isset($_SESSION['UID']))
	 {
		 //get post variable place into variable	 
		isset($_POST['email']) ? $email = $_POST['email'] : Header("Location: ../login.php");
		isset($_POST['password']) ? $password = $_POST['password'] : Header("Location: ../login.php");
		isset($_POST['user-type']) ? $user_type = $_POST['user-type'] : Header("Location: ../login.php"); //faculty | admin
		
		if($user_type == 'faculty')
		{
			$result = mysql_query(" SELECT *
									FROM A_FACULTY
									WHERE Email = '".$email."'
									AND UserPassword = '".$password."'
									LIMIT 1;", $conn) or die(mysql_error());
			$num_result = mysql_num_rows($result);
			$result = mysql_fetch_array($result);
			if ($num_result==1)
			{
				$_SESSION['UID'] = $result['FacultyID']; 	
				$_SESSION['EMAIL'] = $result['Email'];		
				$_SESSION['FULL_NAME'] = $result['FirstName']." ".$result['LastName'];
				$_SESSION['ACCESS_LEVEL'] = "FACULTY";
			}
			else 
			{
				header("Location: ../login.php?message=bad-login");
			}		
		}
		else if($user_type == 'admin')
		{
			$result = mysql_query(" SELECT *
									FROM A_ADMIN
									WHERE Email = '".$email."'
									AND Password = '".$password."'
									LIMIT 1;", $conn) or die(mysql_error());
			$num_result = mysql_num_rows($result);
			$result = mysql_fetch_array($result);
			if ($num_result==1)
			{
				$_SESSION['UID'] = $result['AdminID']; 	
				$_SESSION['EMAIL'] = $result['Email'];		
				$_SESSION['FULL_NAME'] = $result['FirstName']." ".$result['LastName'];
				$_SESSION['ACCESS_LEVEL'] = "SUPER_ADMIN";
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
	}
	include_once('./php/header.php');	

		echo <<<EOT
		<div class="container-fluid">
	      <div class="row">	        
	        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	          <h1 class="page-header">Dashboard</h1>
	          <h2 class="sub-header">Welcome, {$_SESSION['FULL_NAME']}</h2>
	        </div>
	      </div>
	    </div>
EOT;
	
	include_once('./php/footer.php');
?>