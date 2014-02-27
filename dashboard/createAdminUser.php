<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	
	//always initialized variables to be used
	$interactiveMessage = "";

	
?>   
<?php 
	
	//always initialized variables to be used
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$password 			= "";
	$email				= "";

	if(isset($_POST['formSubmit']))
	{
		//take the information submitted and verify inputs
		//always trim the user input to get rid of the additiona white spaces on both ends of the user input
		$firstName 	= trim($_POST['firstname']);
		$lastName	= trim($_POST['lastname']);
		$middleName = trim($_POST['middlename']);
		$password 	= trim($_POST['password']);	
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
		if(emailAddressCheck($email) == flase)
		{
			$interactiveMessage = $interactiveMessage . "Email is not an valid Email Address.<br>";
		}
		else
		{
			$emailIsOk = true;
		}
		
		// Checking passed
		if ($unameok && $pwdok && $agreeok) {
			//you will enter data into the database here
		
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
		}
		
		
	}//end if(isset($_POST['formSubmit']))
	

	

    
?>    

    
    
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Administrator User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createAdminUser.php" class="form-horizontal" role="form" method="post">
			
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
					<input type="text" class="form-control" id="password" placeholder="Password (Required)" name="password">
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
					<input type="submit" name="formSubmit" value="Create Administrator User" />
					<!--  
					<button type="submit" class="btn btn-primary" name="formSubmit">
						Create Database User
					</button>
					-->
				</div>
			</div>
	</form>
    </div>

	</div>

<?php
	include_once('./php/footer.php');
?>