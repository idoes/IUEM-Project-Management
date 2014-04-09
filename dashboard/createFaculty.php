<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	require_once("mail/mail.class.php");
	require_once("dbconnect.php");
	
	/********************************************************************
	* PHP - Page Initialization
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

?>   
<?php 
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
			print "<br>Email Record occur as counter: $count <br>";
			
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
					header('Refresh: 3; URL=dashboard.php?redirect=create-user');
				}
				else
				{
					$interactiveMessage .= "<br>there has some problems during record insertion; please re-insert admin. record again.";
				}
				
				
			}//END ELSE($count != 0)
			
			
		}//END if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
	}





?>
   <!--********************************************************************
		* HTML part 
	***********************************************************************-->		
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Faculty User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createFaculty.php" class="form-horizontal" role="form" method="post">
			
			<!-- screen out interative message which is deliverd from server. -->
			<?php 
			//screen out the issues at the top of page
			if ($interactiveMessage != "")
			{
				print $interactiveMessage;
			}
			$interactiveMessage = "";
			?>
			
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
					<input type="email" class="form-control" id="email" placeholder="Email (Required)" name="email">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-1 control-label">Password:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="password" placeholder="<?php echo $password;?>" name="password" disabled>
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
					<input type="submit" class='btn btn-primary' name="formSubmit" value="Create Faculty/Staff User" />
					<!--  
					<button type="submit" class="btn btn-primary" name="formSubmit">
						Create Database User
					</button>
					-->
				</div>
			</div>
	</form>
    </div>

<?php
	include_once('./php/footer.php');
?>
