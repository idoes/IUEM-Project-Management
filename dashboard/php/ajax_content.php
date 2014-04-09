<?php
	//pass one of the following strings as ajaxID
	//create-user
	//manage-users
	//create-admin
	//manage-admins
	//create-project
	//manage-projects
	//profile
	//help
	
	$requestedPage = $_GET['ajaxID'];
	
	if($requestedPage == 'create-user')
	{
	include_once('../inc/utilityFunction.php');
	require_once("../mail/mail.class.php");
	require_once("../dbconnect.php");
	
	/********************************************************************
	* PHP - Page Initialization
	 *
	***********************************************************************/
	$interactiveMessage = "";
	$password 			= "";
	//password is generated for user and send to user's email for activation purpose
	//The given password is exactly 12 in length, contaning mixed Captical English Letter and single digit
	$password 	= randomCodeGenerator(12);
	
	//test
	/*
	print_r($_POST);
	if(isset($_POST['formSubmit']))
	{
		echo "<br>bootstrap button works in terms of input form post method";
	}
	*/
	
	/*
	 * send Email
	*/
	function sendEmail($emailAddress, $firstName, $userName, $password)
	{
		global $interactiveMessage;
		//now send the email to the username registered for activating the account
		//$code = randomCodeGenerator(50);
		$code = randomCodeGenerator(50);
		$subject = "Email Activation";
		//TODO change the re-direct direcotory || reset password page
		$body = 'Your code is '.$code.
		'<br>Your UserName is '.$userName.
		'<br>Your Password is '.$password.
		'<br>Please click the following link in order to finish registration preocess<br>'.
		'http://corsair.cs.iupui.edu:22071/IUEM/dashboard/validate.php?theQueryString='.$code;
		$mailer = new Mail();
	
		if (($mailer->sendMail($emailAddress, $firstName, $subject, $body)) == true)
			$interactiveMessage .= "<br>A welcome message has been sent to the address. He or she have futher instrunction
									in order to activate his or her account<br>";
		else $interactiveMessage .= "<br>Email not sent. " . $emailAddress.' '. $firstName.' '. $subject.' '. $body;
	
		return $code;
	}

	/********************************************************************
	 * PHP - Page Initialization
	***********************************************************************/
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$email				= "";
	
	/********************************************************************
	 * PHP - IF Subimit
	***********************************************************************/
	if(isset($_POST['formSubmit']))
	{
		//flag for all input validation purpose
		$firstNameIsOk 	= false;
		$lastNameIsOk	= false;
		$middleNameIsOk = false;
		$passwordIsOk 	= false;
		$emailIsOk		= false;
		
		//always trim input values
		$firstName 	= trim($_POST['firstname']);
		$lastName	= trim($_POST['lastname']);
		$middleName = trim($_POST['middlename']);
		$email		= trim($_POST['email']);
		
		// validate text box input 1 - check empty
		if(empty($firstName))
		{
			$interactiveMessage = $interactiveMessage . "<br>First Name can not be empty.<br>";
		}
		if(empty($lastName))
		{
			$interactiveMessage = $interactiveMessage . "<br>Last Name can not be empty.<br>";
		}
		if(empty($password))
		{
			$interactiveMessage = $interactiveMessage . "<br>Password can not be empty.<br>";
		}
		if(empty($email))
		{
			$interactiveMessage = $interactiveMessage . "<br>Email can not be empty.<br>";
		}
		
		//validate text box input 2 - check English letter based
		if(characterCheck($firstName) == false)
		{
			$interactiveMessage = $interactiveMessage . "<br>First Name can only be English based letter.<br>";
		}
		else
		{
			$firstNameIsOk = true;
		}
		if(characterCheck($lastName) == false)
		{
			$interactiveMessage = $interactiveMessage . "<br>Last Name can only be English based letter.<br>";
		}
		else
		{
			$lastNameIsOk = true;
		}
		
		//validate text box input 3 - password qualify
		if(pwdValidate($password) == false)
		{
			$interactiveMessage = $interactiveMessage . "<br>Password should be Longer than 12 characters and alphanumeric letters.<br>";
		}
		else
		{
			$passwordIsOk = true;
		}
		
		//validate text box input 4 - Email qualify
		if(emailAddressCheck($email) == false)
		{
			$interactiveMessage = $interactiveMessage . "<br>Email is not an valid Email Address.<br>";
		}
		else
		{
			$emailIsOk = true;
		}
		
		//TODO
		//validate text box input 5 - mysql_real_escape_string()
		
		
		//all input flag issue are passing
		if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
		{
			//Database activities - check Whether the Record has already occur
			$sqlCountFaculty = "SELECT COUNT(*) AS COUNTER FROM A_FACULTY WHERE Email = '" . $email. "';";
			$resultSqlCountFaculty = mysql_query($sqlCountFaculty, $conn) or die(mysql_error());
			$theObject = mysql_fetch_object($resultSqlCountFaculty);
			$count = $theObject -> COUNTER;
			
			//test
			//print "<br>Email Record occur as counter: $count <br>";
			
			//the Record has already occur
			if ($count != 0)
			{
				//to check IsActivated
				$sqlIsActive = "SELECT IsActive AS IS_ACTIVE FROM A_FACULTY WHERE UserName = '" . $email. "';";
				$resultIsActive = mysql_query($sqlIsActive, $conn) or die(mysql_error());
				$returnObject= mysql_fetch_object($resultIsActive);
				$isActive = $returnObject -> IS_ACTIVE;
				
				//test
				print "is Active: $isActive<br>";
				
				//to check Whether it is an activated account
				if($isActive === "NO")
				{
					//generate new activation code || resend Email
					$activationCode2 = sendEmail($email, $firstName, $email, $password);
					
					//test
					print "ActivationCode2: $activationCode2<br>";
					
					//Upgrade ADIMN.ActivationCode
					$sqlUpgradeACode = "UPDATE A_FACULTY
										SET ActivationCode = '$activationCode2',
										UserPassword = '$password'
										WHERE UserName = '$email';";
					$resultUpdate = mysql_query($sqlUpgradeACode, $conn) or die(mysql_error());
					if ($resultUpdate)
					{
						$interactiveMessage .= "<br>The new password value and activation code haven been reset.<br>";
					}
					else
					{
						$interactiveMessage .= "<br>there has some problems during record updating; please re-insert admin. record again.<br>";
					}
	
				}//END if($isActive === "NO")
				else 
				{
					$interactiveMessage = $interactiveMessage . "<br>The created user has record in Database and has been activated by himself or herself.";
				}//END ELSE($isActive === "NO")

			}//END if ($count != 0)
			else
			{
				//send Email
				$activationCode = sendEmail($email, $firstName, $email, $password);
				
				/********************************************************************
				 * Reference - Datbase Faculty Attribute name
				***********************************************************************/
				/*
				 * FacultyID, 
					FirstName, 
					LastName, 
					MiddleName, 
					Email, 
					Title, 
					Position, 
					OfficeLocation, 
					BioText, 
					BioPhotoLink, 
					CVFileLink, 
					UserName, 
					UserPassword, 
					ActivationCode, 
					IsActive, 
					FirstAccessDate, 
					LastAccessDate
				 */	
						
				//Database FACULTY Insertion
				$sqlFacultyInsertion = "INSERT INTO A_FACULTY
											(	FacultyID, 
												FirstName, 
												LastName, 
												MiddleName, 
												UserName, 
												UserPassword, 
												ActivationCode, 
												IsActive)
											values(
												null,
												'$firstName',
												'$lastName',
												'$middleName',
												'$email',
												'$password',
												'$activationCode',
												'NO');";
				$resultInsertion = mysql_query($sqlFacultyInsertion, $conn) or die(mysql_error());
				if ($resultInsertion)
				{
					$interactiveMessage .= "<br>You information has been inserted into Database successfully.";
				}
				else
				{
					$interactiveMessage .= "<br>there has some problems during record insertion; please re-insert admin. record again.";
				}
				
				
			}//END ELSE($count != 0)
			
			
		}//END if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
	}

echo <<<EOT
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Faculty User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createFaculty.php" class="form-horizontal" role="form" method="post">
			
			<!-- screen out interative message which is deliverd from server. -->
EOT;
			//screen out the issues at the top of page
			if ($interactiveMessage != "")
			{
				print $interactiveMessage;
			}
			$interactiveMessage = "";
echo <<<EOT
		<div class="form-group">
			<label for="firstname" class="col-sm-1 control-label">First Name:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="firstname" placeholder="First Name (Required)" name="firstname">
			</div>
		</div>
		<div class="form-group">
			<label for="middlename" class="col-sm-1 control-label">Middle Name:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="middlename" placeholder="Middle Name" name="middlename">
			</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-1 control-label">Last Name:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="lastname" placeholder="Last Name (Required)" name="lastname">
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-sm-1 control-label">Email</label>
			<div class="col-sm-4">
				<input autocomplete="off" type="email" class="form-control" id="email" placeholder="Email (Required)" name="email">
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-sm-1 control-label">Password:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="password" placeholder="{$password}" name="password" disabled>
			</div>
		</div>
		<!-- see note 4  
		<div class="form-group">
			<label for="position" class="col-sm-1 control-label">Access Level:</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" id="accessLevel" placeholder="Administrator or Faculty Member (Required)" name="accessLevel">
			</div>
		</div>
		-->
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-10">
				Note1: Only Administrator can create, view, update, and delete an Database user record.<br>
				Note2: We have perfomed create rule in this file. we may integrate update rule and delete rule with
						view rule since updating and deleting need to be performed only after viewing.<br>
				Note3: This file is intended to perfom the same action as createUser.php. <br>
				Task1:	Database Table[ADMIN] add attribute MiddleName.<br>
				Task2: 	create a file to view || update || delete Database user.<br>
				Note4: If the definition for ADMIN table is all the people who can access this DB and the definition for 
						FACULTY is all the people who have been involved into a project as Researcher, or PI, or Co-PI,
						then ADMIN-AccessLevel attribute is useless.<br>
				Note5: Assume we have ADMIN table and FACULTY table, how could a faculty (a Database user) edit his or 
						her profile in FACULTY table?<br>
				Note6: Assume we have ADMIN table and FACULTY table separately, how could we obtain current user's ID and 
						hold it into a SESSION variable? Be specific, If the same person is both a administrator and 
						faculty, this person's data is duplicated in ADMIN table and FACULTY table. This is not a 
						problem. When this person is logged in, we can hold his or her identity from ADMIN.Email and 
						store this value into SESSION. But, how could we pull out all the projects belong to him or her? 
						Since in FACULTY table, this person's identity is using an auto-incremented key. Besides, we can 
						make the Email attribute in FACULTY as PK. But, a database user may change this FACULTY.Email 
						value later on. In other words, there is a chance that ADMIN.Email and FACULTY.Email would not 
						be equal all the time.<br>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-10">
				<br/>
				<!-- use input instead button -->
				<input type="submit" class='btn btn-primary' name="formSubmit" value="Create Faculty User" />
				<!--  
				<button type="submit" class="btn btn-primary" name="formSubmit">
					Create Database User
				</button>
				-->
			</div>
		</div>
</form>
</div>
EOT;
	}
	else if($requestedPage == 'manage-users')
	{
	require_once("../dbconnect.php");
	
	//TODO
	/*************************************************************
	 * DB activity - Check SESSION vaiable against DB.ADMIN
	*************************************************************/
	
	
	/********************************************************************
	 * Reference - Datbase Faculty Attribute name
	***********************************************************************/
	/*
	 * FacultyID,
		FirstName,
		LastName,
		MiddleName,
		Email,
		Title,
		Position,
		OfficeLocation,
		BioText,
		BioPhotoLink,
		CVFileLink,
		UserName,
		UserPassword,
		ActivationCode,
		IsActive,
		FirstAccessDate,
		LastAccessDate
	*/
	
	
	
	
	
	/*************************************************************
	 * DB activity - Fetch record from DB.ADMIN and build related XML
	*************************************************************/
	$sql = "SELECT * FROM A_FACULTY;";
	$result = mysql_query($sql, $conn) or die(mysql_error());
	$xml = new SimpleXMLElement('<xml/>');
	while($row = mysql_fetch_assoc($result))
	{
		$facultyInstance = $xml->addChild('FacultyInstance');
		$facultyInstance->addChild('FacultyID', 		$row['FacultyID']);
		$facultyInstance->addChild('FirstName', 		$row['FirstName']);
		$facultyInstance->addChild('LastName',	 		$row['LastName']);
		$facultyInstance->addChild('MiddleName', 		$row['MiddleName']);
		$facultyInstance->addChild('Email', 			$row['Email']);
		$facultyInstance->addChild('Title', 			$row['Title']);
		$facultyInstance->addChild('Position', 			$row['Position']);
		$facultyInstance->addChild('OfficeLocation', 	$row['OfficeLocation']);
		$facultyInstance->addChild('BioText', 			$row['BioText']);
		$facultyInstance->addChild('BioPhotoLink', 		$row['BioPhotoLink']);
		$facultyInstance->addChild('CVFileLink', 		$row['CVFileLink']);
		$facultyInstance->addChild('UserName', 			$row['UserName']);
		$facultyInstance->addChild('UserPassword', 		$row['UserPassword']);
		$facultyInstance->addChild('ActivationCode', 	$row['ActivationCode']);
		$facultyInstance->addChild('IsActive', 			$row['IsActive']);
		$facultyInstance->addChild('FirstAccessDate', 	$row['FirstAccessDate']);
		$facultyInstance->addChild('LastAccessDate', 	$row['LastAccessDate']);
	
	}//end while ($row = mysql_fetch_assoc($result))
	
	/*************************************************************
	 * write $xml variable to a file on the server
	*************************************************************/
	//$fp = fopen("../xml/Table-Solo-Faculty.xml","wb");
	//fwrite($fp, $xml->asXML());
	//fclose($fp);

echo <<<EOT
<!--********************************************************************
		* HTML part 
	***********************************************************************-->
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <!-- TODO use session variable to get user's full name -->
      <h1 class="page-header">Current Faculty</h1>
</div>
    
<div class="container-fluid">
	<div class="row">	        
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Middle Name</th>
						<th>User Name</th>
						<th>User Password</th>
						<th>Activation Code</th>
						<th>Activated</th>
						<th>First Access Date</th>
						<th>Last Access Date</th>
						<th>Edit</th>
					</tr>
EOT;
						//TODO
						//Add for loop to go through user DB and print out tr/td info
						$facultyRecord = simplexml_load_file('../xml/Table-Solo-Faculty.xml');
						$i = -1;
						foreach ($facultyRecord as $index)
						{
							//get the index for this associated array
							$i++;
							//prepare for query string
							$get = $_GET;
							$get['facultyXMLID'] = $i;
							$theQueryString = http_build_query($get);
							print <<<HERE
							<tr>
								<td>$index->FacultyID</td>
								<td>$index->FirstName</td>
								<td>$index->LastName</td>
								<td>$index->MiddleName</td>
								<td>$index->UserName</td>
								<td>$index->UserPassword</td>
								<td>$index->ActivationCode</td>
								<td>$index->IsActive</td>
								<td>$index->FirstAccessDate</td>
								<td>$index->LastAccessDate</td>
								<td><a href="editSingleFaculty.php?$theQueryString">Edit</a></td>
							</tr>
HERE;
							//test
							//echo "<br>" .$i;
}//end foreach ($adminRecord as $index)
	}
	else if($requestedPage == "create-admin")
	{
	include_once('../inc/utilityFunction.php');
	require_once "../mail/mail.class.php";
	require_once "../dbconnect.php";
	
	//always initialized variables to be used
	$interactiveMessage = "";
	$password 			= "";
	//password is generated for user and send to user's email for activation purpose
	//The given password is exactly 12 in length, contaning mixed Captical English Letter and single digit
	$password 	= randomCodeGenerator(12);
	
	
	/*
	 * send Email
	*/
	function sendEmail($emailAddress, $firstName, $userName, $password)
	{
		global $interactiveMessage;
		//now send the email to the username registered for activating the account
		//$code = randomCodeGenerator(50);
		$code = randomCodeGenerator(50);
		$subject = "Email Activation";
		//TODO change the re-direct direcotory || reset password page
		$body = 'Your code is '.$code.
				'<br>Your UserName is '.$userName.
				'<br>Your Password is '.$password.
				'<br>Please click the following link in order to finish registration preocess<br>'.
				'http://corsair.cs.iupui.edu:22071/IUEM/dashboard/validate.php?theQueryString='.$code;
		$mailer = new Mail();
	
		if (($mailer->sendMail($emailAddress, $firstName, $subject, $body)) == true)
			$interactiveMessage .= "A welcome message has been sent to the address. He or she have futher instrunction 
									in order to activate his or her account";
		else $interactiveMessage .= "Email not sent. " . $emailAddress.' '. $firstName.' '. $subject.' '. $body;
	
		return $code;
	}
	
	//always initialized variables to be used
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$email				= "";

	if(isset($_POST['formSubmit']))
	{
		//take the information submitted and verify inputs
		//always trim the user input to get rid of the additiona white spaces on both ends of the user input
		$firstName 	= trim($_POST['firstname']);
		$lastName	= trim($_POST['lastname']);
		$middleName = trim($_POST['middlename']);
		$email		= trim($_POST['email']);
		
		$firstNameIsOk 	= false;
		$lastNameIsOk	= false;
		$middleNameIsOk = false;
		$passwordIsOk 	= false;	
		$emailIsOk		= false;
/*	
		$formInputArray = array (
				'firstName' 	=> array('field' => 'TextBox[First Name]', 'value' 	=> $firstName),
				'middleName' 	=> array('field' => 'TextBox[last Name]',  'value' 	=> $middleName),
				'lastName' 		=> array('field' => 'TextBox[last Name]',  'value' 	=> $firstName),
				'password' 		=> array('field' => 'TextBox[Password]',   'value' 	=> $password),
				'email' 		=> array('field' => 'TextBox[Email]',      'value'	=> $email)
		);
*/		
	
		// validate text box input 1 - check empty
		if(empty($firstName))
		{
			$interactiveMessage = $interactiveMessage . "First Name can not be empty.<br>";
		}
		if(empty($lastName))
		{
			$interactiveMessage = $interactiveMessage . "Last Name can not be empty.<br>";
		}
		if(empty($password))
		{
			$interactiveMessage = $interactiveMessage . "Password can not be empty.<br>";
		}
		if(empty($email))
		{
			$interactiveMessage = $interactiveMessage . "Email can not be empty.<br>";
		}
		
		//validate text box input 2 - check English letter based
		if(characterCheck($firstName) == false)
		{
			$interactiveMessage = $interactiveMessage . "First Name can only be English based letter.<br>";
		}
		else
		{
			$firstNameIsOk = true;
		}
		if(characterCheck($lastName) == false)
		{
			$interactiveMessage = $interactiveMessage . "Last Name can only be English based letter.<br>";
		}
		else
		{
			$lastNameIsOk = true;
		}
		
		//validate text box input 3 - password qualify
		if(pwdValidate($password) == false)
		{
			$interactiveMessage = $interactiveMessage . "Password should be Longer than 12 characters and alphanumeric letters.<br>";
		}
		else
		{
			$passwordIsOk = true;
		}
		
		//validate text box input 4 - Email qualify
		if(emailAddressCheck($email) == false)
		{
			$interactiveMessage = $interactiveMessage . "Email is not an valid Email Address.<br>";
		}
		else
		{
			$emailIsOk = true;
		}
		
		//validate text box input 5 - SQL Injection
		//first escapse all the strings so that backslashes are added before the following characters: \x00, \n, \r, \, ', " and \x1a.
		//This is used to prevent sql injections.
		//$firstName 			= mysql_real_escape_string($firstName);
		//$lastName			= mysql_real_escape_string($lastName);
		//$middleName 		= mysql_real_escape_string($middleName);
		//$password 			= mysql_real_escape_string($password);
		//$email				= mysql_real_escape_string($email);
		
		
		$interactiveMessage.="<br/><br/>";

		// Checking passed
		if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk) 
		{
			//you will enter data into the database here
			/*************************************************************
			* Database activities - check Whether the Record has already occur
			*************************************************************/
			//1st check if the EmailAddress already exists in the database
			$sqlCountAdmin = "SELECT COUNT(*) AS COUNTER FROM A_ADMIN WHERE Email = '" . $email. "';";
			//test
			//print "$sqlCountAdmin<br>";
			//send the query to the database or quit if cannot connect || hold the result set
			$result = mysql_query($sqlCountAdmin, $conn) or die(mysql_error());
			//the query results are objects, in this case, one object
			$field = mysql_fetch_object($result);
			$count = $field -> COUNTER;
			//test
			print "Email Record occur as counter: $count <br>";
			
			
			
			
			/*************************************************************
			 * Database activities - the Record has already occur
			*************************************************************/
			if ($count != 0)
			{
				//to check IsActive or not
				$sqlIsActive = "SELECT IsActive AS IS_ACTIVE FROM A_ADMIN WHERE Email = '" . $email. "';";
				$resultIsActive = mysql_query($sqlIsActive, $conn) or die(mysql_error());
				$returnObject= mysql_fetch_object($resultIsActive);
				$isActive = $returnObject -> IS_ACTIVE;
				//test
				print "is Active: $isActive<br>";
				
				if($isActive === "NO")
				{
					//generate new activation code
					//send emial with actication code again
					//send Email
					$activationCode2 = sendEmail($email, $firstName, $email, $password);
					//test
					print "ActivationCode2: $activationCode2<br>";
					//Upgrade ADIMN.ActivationCode
					$sqlUpgradeACode = "UPDATE A_ADMIN
										SET ActivationCode = '$activationCode2',
											Password = '$password' 
										WHERE Email = '$email';";
					$resultUpdate = mysql_query($sqlUpgradeACode, $conn) or die(mysql_error());
					if ($resultUpdate)
					{
						$interactiveMessage .= "The new password value and activation code haven been reset.<br>";
					}
					else
					{
						$interactiveMessage .= "there has some problems during record updating; please re-insert admin. record again.<br>";
					}
				}
				else 
				{
					$interactiveMessage = $interactiveMessage . "The created user has record in Database and has been activated by himself or herself.";
				}

			}//end if ($count != 0)
			/*************************************************************
			 * Database activities - the Record has NOT already occur
			*************************************************************/
			else
			{
				//send Email
				$activationCode = sendEmail($email, $firstName, $email, $password);
				//test
				//print "$activationCode<br>";
				//hash the password
				//$password = sha1($password);
			
				$sql = "INSERT INTO A_ADMIN values(
							null,
							'$email',
							'$password',
							null,
							null,
							'$firstName',
							'$lastName',
							'$middleName',
							'$activationCode',
							'NO');";
				//send the query to the database or quit if cannot connect || hold the result set
				$result = mysql_query($sql, $conn) or die(mysql_error());
				if ($result)
				{
					$interactiveMessage .= "You information has been inserted into Database successfully.";
				}
				else 
				{
					$interactiveMessage .= "there has some problems during record insertion; please re-insert admin. record again.";
				}
			}//end else count == 0
			
			//now send the email to the username registered for activating the account
/*			
			$code = randomCodeGenerator(30);
			$subject = "Email Activation";
		
			$body = 'Your code is '.$code;
			$mailer = new Mail();
			if (($mailer->sendMail($uname, $fn, $subject, $body))==true)
				$msg = "<b>Thank you for registering. A welcome message has been sent to the address you have just registered.</b>";
			else $msg = "Email not sent. " . $uname.' '. $fn.' '. $subject.' '. $body;
*/				
			//direct to the next page if necessary
			//Header ("Location:process.php?fn=".$fn."&ln=".$ln."&g=".$gender."&s=".$state."&b=".$birthYear) ;
		}// end if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk) 
		
		
	}//end if(isset($_POST['formSubmit']))    

    
echo <<<EOT
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Administrator User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createAdminUser.php" class="form-horizontal" role="form" method="post">
EOT;
			if ($interactiveMessage != "")
			{
				print $interactiveMessage."<br/><br/>";
			}
			$interactiveMessage = "";
echo <<<EOT
			<div class="form-group">
				<label for="firstname" class="col-sm-1 control-label">First Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="firstname" placeholder="First Name (Required)" name="firstname">
				</div>
			</div>
			<div class="form-group">
				<label for="middlename" class="col-sm-1 control-label">Middle Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="middlename" placeholder="Middle Name" name="middlename">
				</div>
			</div>
			<div class="form-group">
				<label for="lastname" class="col-sm-1 control-label">Last Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="lastname" placeholder="Last Name (Required)" name="lastname">
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-1 control-label">Email</label>
				<div class="col-sm-4">
					<input autocomplete="off" type="email" class="form-control" id="email" placeholder="Email (Required)" name="email">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-1 control-label">Password:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="password" placeholder="{$password}" name="password" disabled>
				</div>
			</div>
			<!-- see note 4  
			<div class="form-group">
				<label for="position" class="col-sm-1 control-label">Access Level:</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="accessLevel" placeholder="Administrator or Faculty Member (Required)" name="accessLevel">
				</div>
			</div>
			-->
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					Note1: Only Administrator can create, view, update, and delete an Database user record.<br>
					Note2: We have perfomed create rule in this file. we may integrate update rule and delete rule with
							view rule since updating and deleting need to be performed only after viewing.<br>
					Note3: This file is intended to perfom the same action as createUser.php. <br>
					Task1:	Database Table[ADMIN] add attribute MiddleName.<br>
					Task2: 	create a file to view || update || delete Database user.<br>
					Note4: If the definition for ADMIN table is all the people who can access this DB and the definition for 
							FACULTY is all the people who have been involved into a project as Researcher, or PI, or Co-PI,
							then ADMIN-AccessLevel attribute is useless.<br>
					Note5: Assume we have ADMIN table and FACULTY table, how could a faculty (a Database user) edit his or 
							her profile in FACULTY table?<br>
					Note6: Assume we have ADMIN table and FACULTY table separately, how could we obtain current user's ID and 
							hold it into a SESSION variable? Be specific, If the same person is both a administrator and 
							faculty, this person's data is duplicated in ADMIN table and FACULTY table. This is not a 
							problem. When this person is logged in, we can hold his or her identity from ADMIN.Email and 
							store this value into SESSION. But, how could we pull out all the projects belong to him or her? 
							Since in FACULTY table, this person's identity is using an auto-incremented key. Besides, we can 
							make the Email attribute in FACULTY as PK. But, a database user may change this FACULTY.Email 
							value later on. In other words, there is a chance that ADMIN.Email and FACULTY.Email would not 
							be equal all the time.<br>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<br/>
					<!-- use input instead button -->
					<input type="submit" class='btn btn-primary' name="formSubmit" value="Create Administrator User" />
					<!--  
					<button type="submit" class="btn btn-primary" name="formSubmit">
						Create Database User
					</button>
					-->
				</div>
			</div>
	</form>
    </div>

EOT;
	}
	else if($requestedPage == 'manage-admins')
	{

	require_once "../dbconnect.php";
	
	//always initialized variables to be used
	/*
	$adminID 			= "";
	$userName			= "";
	$userPassword		= "";
	$firstAccessDate	= "";
	$lastAccessDate 	= "";
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$activationCode		= "";
	$isActivated		= "";
	*/
	//TODO
	/*************************************************************
	 * DB activity - Check SESSION vaiable against DB.ADMIN
	*************************************************************/
	
	
	
	
	/*************************************************************
	 * DB activity - Fetch record from DB.ADMIN
	*************************************************************/
	// SQL Creation to read all values in department column
	$sql = "SELECT * FROM A_ADMIN;";
	//send the query to the database or quit if cannot connect || hold the result set
	$result = mysql_query($sql, $conn) or die(mysql_error());
	//test
	//print_r($result);
	//initialize variable which will store out xml code
	$xml = new SimpleXMLElement('<xml/>');
	//parse results into XML format
	// ProjectID Title Abstract InitialDate LongText CloseDate
	while($row = mysql_fetch_assoc($result))
	{
		$AdminInstance = $xml->addChild('AdminInstance');
		$AdminInstance->addChild('AdminID', $row['AdminID']);
		$AdminInstance->addChild('Email', $row['Email']);
		$AdminInstance->addChild('Password', $row['Password']);
		$AdminInstance->addChild('FirstAccessDate', $row['FirstAccessDate']);
		$AdminInstance->addChild('LastAccessDate', $row['LastAccessDate']);
		$AdminInstance->addChild('FirstName', $row['FirstName']);
		$AdminInstance->addChild('LastName', $row['LastName']);
		$AdminInstance->addChild('MiddleName', $row['MiddleName']);
		$AdminInstance->addChild('ActivationCode', $row['ActivationCode']);
		$AdminInstance->addChild('IsActive', $row['IsActive']);
	
	}//end while ($row = mysql_fetch_assoc($result))
	
	/*************************************************************
	 * write $xml variable to a file on the server
	*************************************************************/
	//$fp = fopen("../xml/Table-Solo-Admin.xml","wb");
	//fwrite($fp, $xml->asXML());
	//fclose($fp);
echo <<<EOT
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <!-- TODO use session variable to get user's full name -->
      <h1 class="page-header">Current Admins</h1>
 </div>
    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Middle Name</th>
						<th>User Name</th>
						<th>User Password</th>
						<th>Activation Code</th>
						<th>Activated</th>
						<th>First Access Date</th>
						<th>Last Access Date</th>
						<th>Edit</th>
					</tr>
EOT;
						//TODO
						//Add for loop to go through user DB and print out tr/td info
						$adminRecord = simplexml_load_file('../xml/Table-Solo-Admin.xml');
						$i = -1;
						foreach ($adminRecord as $index)
						{
							//get the index for this associated array
							$i++;
							//prepare for query string
							$get = $_GET;
							$get['AdminXMLID'] = $i;
							$theQueryString = http_build_query($get);
							print <<<HERE
							<tr>
								<td>$index->AdminID</td>
								<td>$index->FirstName</td>
								<td>$index->LastName</td>
								<td>$index->MiddleName</td>
								<td>$index->Email</td>
								<td>$index->Password</td>
								<td>$index->ActivationCode</td>
								<td>$index->IsActive</td>
								<td>$index->FirstAccessDate</td>
								<td>$index->LastAccessDate</td>
								<td><a href="editSingleAdmin.php?$theQueryString">Edit</a></td>
							</tr>
HERE;
							//test
							//echo "<br>" .$i;
						}//end foreach ($adminRecord as $index)
echo <<<EOT
				</table>
			</div>
		</div>
	</div>
</div>
EOT;
}
	else if($requestedPage == 'create-project')
	{
	echo <<<EOT
	   <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	      <h1 class="page-header">Create a Project</h1>
	    </div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<form action="createProject.php" class="form-horizontal" role="form" method="post">
				<div class="form-group">
					<label for="projecttitle" class="col-sm-1 control-label">Title:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="projecttitle" placeholder="Project Title" name="projecttitle">
					</div>
				</div>
				<div class="form-group">
					<label for="projecttitle" class="col-sm-1 control-label">Abstract:</label>
					<div class="col-sm-4">
						<textarea rows="6" class="form-control" id="projectAbstrct" placeholder="Project Abstract" name="projectAbstract"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-sm-1 control-label">Description:</label>
					<div class="col-sm-4">
						<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="startdate" class="col-sm-1 control-label">Project Start Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
					</div>
				</div>
				<div class="form-group">
					<label for="enddate" class="col-sm-1 control-label">Project End Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspector" class="col-sm-1 control-label">Project Inspector Name:</label>
					<div class="col-sm-4">
						<input autocomplete="off" value="" type="text" list="txtHint" class="form-control" id="projectInspector" placeholder="Project Inspector Name" name="projectInspector" onkeyup="showHint(this.value)">
						<datalist id="txtHint"></datalist>
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspectorStartDate" class="col-sm-1 control-label">project Inspector Start Date:</label>
					<div class="col-sm-4">
						<input value="" type="text" class="form-control" id="projectInspectorStartDate" placeholder="project Inspector Start Date" name="facultystartdate">
					</div>
				</div>
				<!--div class="form-group">
					<div class="control-group">
						<label for="projectCoInspector" class="col-sm-1 control-label">Project Co-Inspector:</label>
						<div class="col-sm-4">
							<input value="" type="text" value="NULL" class="form-control" id="projectCoInspector" placeholder="Project Co-Inspector" name="projectCoInspector">
						</div>
					</div>
				</div-->
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button id='addButton' type='button' class="btn btn-primary">
							Add Project Co-PI (Optional)
						</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button id='removeButton' type='button' class="btn btn-primary">
							Remove Last CO-PI
						</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button action="editSingleProject.php" type="submit" class="btn btn-primary">
							Create Project
						</button>
					</div>
				</div>
				<br/>
			</form>
			</div>
		</div>
	</div>
EOT;
	}
	else if($requestedPage == 'manage-projects')
	{
	include_once('../dbconnect.php');
echo <<<EOT
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Manage Projects</h1>
 </div>    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#</th>
						<th>Project Title</th>
						<th>Created On</th>
						<th>PI</th>
						<th>Faculty Options</th>
						<th>Add Files</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
EOT;
	//TODO
	//Add for loop to go through project DB and print out tr/td info
	$result = mysql_query(" SELECT *
							FROM A_PROJECT;");

	$num_rows = mysql_num_rows($result);
	
	$i = 1;

	while($row = mysql_fetch_assoc($result))
	{
		$project_id = $row['ProjectID'];
		echo "<tr><td>".$i."</td>";
		echo "<td>".$row['Title']."</td>";
		echo "<td>".$row['InitialDate']."</td>";
		$user_query = mysql_query(" SELECT FacultyID
									from A_MANAGEMENT
									WHERE ProjectID = '".$project_id."'
									AND Responsibility='PI' LIMIT 1;");
											
		if(mysql_num_rows($user_query) == 0)
		{
			$user_query=array();
			$user_query['FacultyID'] = 'PI Not Set';
		} else {
			$user_query = mysql_fetch_array($user_query);
	                $faculty_query = mysql_query( " SELECT FirstName, LastName
                                                        FROM A_FACULTY
                                                        WHERE FacultyID = '".$user_query['FacultyID']."'
                                                        LIMIT 1;" );
                                                                                
	                $faculty_query = mysql_fetch_array($faculty_query);
		}

		if($user_query['FacultyID'] !== 'PI Not Set')
		{
			echo "<td>".$faculty_query['FirstName']." ".$faculty_query['LastName']."</td>";
		} else {
			echo "<td>PI Not Set</td>";	
		}
		echo "<td><button type='button' class='btn btn-primary btn-xs' onclick=window.location='editAssociatedFaculty.php?projectID=$project_id'>Add/Remove Faculty</button></td>";
		echo "<td><button type='button' class='btn btn-primary btn-xs' onclick=window.location='fileUpload.php?projectID=$project_id'>Add Files</button></td>";
		echo "<td><button type='button' class='btn btn-primary btn-xs' onclick=window.location='editSingleProject.php?projectID=$project_id'>Edit Project</button></td>"; 
		echo "<td><button type='button' class='btn btn-danger btn-xs' onclick=window.location='deleteProject.php?projectID=$project_id'>Delete Project</button></td>";
		$i++;
	}
	

echo <<<EOT
				</table>
			</div>
		</div>
	</div>
</div>
EOT;
	}
	else if($requestedPage == 'profile')
	{
	include_once('../dbconnect.php');
	
	$table_content = "";
	session_start();
	if($_SESSION['ACCESS_LEVEL'] === 'SUPER_ADMIN')
	{
		$result = mysql_query(" SELECT *
								FROM A_ADMIN
								WHERE AdminID = ".$_SESSION['UID'].";", $conn);
																
		$result = mysql_fetch_array($result);
		
  		$table_content.="<tr><td><b>Email:</b></td>
						  <td>".$result['Email']."</td>
						  </tr>
						  <tr>
						  <td><b>First Access Date:</b></td>
						  <td>".$result['FirstAccessDate']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Access Date:</b></td>
						  <td>".$result['LastAccessDate']."</td>
						  </tr>
						  <tr>
						  <td><b>First Name:</b></td>
						  <td>".$result['FirstName']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Name:</b></td>
						  <td>".$result['LastName']."</td>
						  </tr>
						  <tr>
						  <td><b>Middle Name:</b></td>
						  <td>".$result['MiddleName']."</td>
						 </tr>";
		
	} else if($_SESSION['ACCESS_LEVEL'] === 'FACULTY') {
		$result = mysql_query(" SELECT *
								FROM A_FACULTY
								WHERE FacultyID = ".$_SESSION['UID'].";", $conn);
																
		$result = mysql_fetch_array($result);
  		$table_content.="<tr><td><b>First Name:</b></td>
						  <td>".$result['FirstName']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Name:</b></td>
						  <td>".$result['LastName']."</td>
						  </tr>
						  <tr>
						  <td><b>Middle Name::</b></td>
						  <td>".$result['MiddleName']."</td>
						  </tr>
						  <tr>
						  <td><b>Email:</b></td>
						  <td>".$result['UserName']."</td>
						  </tr>
						  <tr>
						  <td><b>First Access Date:</b></td>
						  <td>".$result['FirstAccessDate']."</td>
						  </tr>
						  <!--tr>
						  <td><b></b></td>
						  <!--TODO: Add here implementation for lastaccessdate update it on login-->
						  <td></td>
						 </tr-->";
	}
	
echo <<<EOT
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h1 class="page-header">My Profile</h1>
</div>

<div class="col-sm-9 col-sm-offset-3 col-md-5 col-md-offset-2 main">
	<table class="table">
  		{$table_content}
	</table>
	<!-- The following need to be delete after PHP implementation. -->
	<div class="col-sm-offset-0 col-sm-10">
	<br/>
		<!--button type="submit" class="btn btn-primary btn-lg">
			Edit My Profile
		</button-->
	</div>	
</div>
EOT;

	}
	else if($requestedPage == 'help')
	{
echo <<<EOT
    <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Help Page</h1>
          <h2 class="sub-header">Learn how to use this tool here.</h2>
        </div>
      </div>
    </div>
EOT;
	}
	else
	{
		echo 'Sorry, that content was not found.';	
	}
?>
