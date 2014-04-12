<?php
	include_once('inc/utilityFunction.php');
	require_once "mail/mail.class.php";
	require_once "dbconnect.php";
	include_once('php/header.php');
	
	
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
									in order to activate his or her account<br/>";
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
							'0000-00-00 00:00:00',
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
					$interactiveMessage .= "Your information has been inserted into Database successfully.";
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
    
EOT;

if ($interactiveMessage != "")
			{
				print "<div class=\"col-sm-6 col-sm-offset-3 col-md-10 col-md-offset-2 main\">
	<div class=\"row bg-danger\">
	 		<center><br/>".$interactiveMessage."<br/></center>
	 	</div>
	    </div>";
			}
			$interactiveMessage = "";
echo <<<EOT
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createAdminUser.php" class="form-horizontal" role="form" method="post">
EOT;
			
echo <<<EOT
			<div class="form-group">
				<label for="firstname" class="col-sm-2 control-label">First Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="firstname" placeholder="First Name (Required)" name="firstname">
				</div>
			</div>
			<div class="form-group">
				<label for="middlename" class="col-sm-2 control-label">Middle Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="middlename" placeholder="Middle Name" name="middlename">
				</div>
			</div>
			<div class="form-group">
				<label for="lastname" class="col-sm-2 control-label">Last Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="lastname" placeholder="Last Name (Required)" name="lastname">
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-6">
					<input autocomplete="off" type="email" class="form-control" id="email" placeholder="Email (Required)" name="email">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Password:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="password" placeholder="{$password}" name="password" disabled>
				</div>
			</div>
			<!-- see note 4  
			<div class="form-group">
				<label for="position" class="col-sm-2 control-label">Access Level:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="accessLevel" placeholder="Administrator or Faculty Member (Required)" name="accessLevel">
				</div>
			</div>
			-->
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

include_once('php/footer.php');
?>