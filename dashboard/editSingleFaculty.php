<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	require_once "dbconnect.php";
	
	
	
	/********************************************************************
	 * Reference - Datbase Faculty Attribute name Count as 17
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
	
	//TODO
	/*************************************************************
	 * DB activity - Check SESSION vaiable against DB.ADMIN
	*************************************************************/
	
	
	/********************************************************************
	 * PHP - Page Initialization
	***********************************************************************/
	$interactiveMessage = "";
	$facultyID 		= "";
	
	$facultyID 			= "";
	$userName			= "";
	$userPassword		= "";
	//$firstAccessDate	= "";
	//$lastAccessDate 	= "";
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$email				= "";
	$title				= "";
	$position			= "";
	$officeLocation		= "";
	$bioText			= "";
	$bioPhotoLink		= "";
	$cvFileLink			= "";
	$activationCode		= "";
	$isActivated		= "";

?>
<?php 
	/********************************************************************
	 * PHP - form is submitted
	***********************************************************************/
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$facultyID = $_GET['facultyID'];	
		//test
		//echo "<br>This is After Submit";
		
		/*************************************************************
		 * Input trimming
		*************************************************************/
		$userName			= mysql_escape_string(trim($_POST['username']));
		$userPassword		= mysql_escape_string(trim($_POST['userpassword']));
		//$firstAccessDate	= trim($_POST['firstaccessdate']);
		//$lastAccessDate 	= trim($_POST['lastaccessdate']);
		$firstName 			= mysql_escape_string(trim($_POST['firstname']));
		$lastName			= mysql_escape_string(trim($_POST['lastname']));
		$middleName 		= mysql_escape_string(trim($_POST['middlename']));
		$email				= mysql_escape_string(trim($_POST['email']));
		$title				= mysql_escape_string(trim($_POST['title']));
		$position			= mysql_escape_string(trim($_POST['position']));
		$officeLocation		= mysql_escape_string(trim($_POST['officeLocation']));
		$bioText			= mysql_escape_string(trim($_POST['biotext']));
		$bioPhotoLink		= mysql_escape_string(trim($_POST['biophotolink']));
		$cvFileLink			= mysql_escape_string(trim($_POST['cvfilelink']));
		//$activationCode		= trim($_POST['activationcode']);
		$isActivated		= mysql_escape_string(trim($_POST['isactivated']));
		
		
		//TODO
		/*************************************************************
		 * Input validation
		*************************************************************/
		$userNameOK 		= true;
		$userPasswordOK 	= true;
		$firstNameOK		= true;
		$lastNameOK			= true;
		$middleNameOK 		= true;
		$emailOK			= true;
		$titleOK			= true;
		$positionOK			= true;
		$bioTextOK			= true;
		$bioPhotoLinkOK		= true;
		$cvFileLinkOK		= true;
		$activationCodeOK	= true;
		$isActivatedOK		= true;
		
		// validate text box input 1 - check empty
		if(empty($firstName))
		{
			$firstNameOK		= false;
			$interactiveMessage = $interactiveMessage . "First Name can not be empty.<br>";
		}
		if(empty($lastName))
		{
			$lastNameOK			= false;
			$interactiveMessage = $interactiveMessage . "Last Name can not be empty.<br>";
		}
		if(empty($userPassword))
		{
			$userPasswordOK 	= false;
			$interactiveMessage = $interactiveMessage . "User Password can not be empty.<br>";
		}
		if(empty($userName))
		{
			$userNameOK 		= false;
			$interactiveMessage = $interactiveMessage . "User Name can not be empty.<br>";
		}
		
		//validate text box input 2 - check English letter based
		if(characterCheck($firstName) == false)
		{
			$firstNameOK		= false;
			$interactiveMessage = $interactiveMessage . "First Name can only be English based letter.<br>";
		}
		if(characterCheck($lastName) == false)
		{
			$lastNameOK			= false;
			$interactiveMessage = $interactiveMessage . "Last Name can only be English based letter.<br>";
		}

		//validate text box input 3 - password qualify
		if(pwdValidate($userPassword) == false)
		{
			$userPasswordOK 	= false;
			$interactiveMessage = $interactiveMessage . "User Password should be Longer than 12 characters and alphanumeric letters.<br>";
		}
		
		//validate text box input 4 - Email qualify
		if(emailAddressCheck($userName) == false)
		{
			$userNameOK 		= false;
			$interactiveMessage = $interactiveMessage . "User Name is not an valid Email Address.<br>";
		}
		
		//TODO
		//validate text box input 5 - mysql_real_escape_string()
		
		// Checking passed
		if ($userNameOK 		&&
			$userPasswordOK 	&&
			$firstNameOK		&&
			$lastNameOK			&&
			$middleNameOK 		&&
			$emailOK			&&
			$titleOK			&&
			$positionOK			&&
			$bioTextOK			&&
			$bioPhotoLinkOK		&&
			$cvFileLinkOK		&&
			$activationCodeOK	&&
			$isActivatedOK)
		{
			
			/*************************************************************
			 * DB activity - Ensure User name is unique
			*************************************************************/
			$sqlCountFaculty = "SELECT COUNT(*) AS COUNTER FROM A_FACULTY WHERE Email = '" . $userName. "';";
			$resultSqlCountFaculty = mysql_query($sqlCountFaculty, $conn) or die(mysql_error());
			$theObject = mysql_fetch_object($resultSqlCountFaculty);
			$count = $theObject -> COUNTER;
			
			if($count != 0)
			{
				$interactiveMessage .= "User name value is duplicated with others user name.<br>";
			}
			else
			{
				//TODO
				/*************************************************************
				 * DB activity - update DB.Faculty by providing Faculty ID
				*************************************************************/
				$sqlUpgradeFaculty = "UPDATE A_FACULTY
				SET	FirstName 		= '$firstName',
					LastName 		= '$lastName',
					MiddleName 		= '$middleName',
					Email 			= '$email',
					Title 			= '$title',
					Position 		= '$position',
					OfficeLocation 	= '$officeLocation',
					BioText 		= '$bioText',
					BioPhotoLink 	= '$bioPhotoLink',
					CVFileLink 		= '$cvFileLink',
					UserName 		= '$userName',
					UserPassword 	= '$userPassword',
					ActivationCode 	= '$activationCode',
					IsActive 		= '$isActivated'
				WHERE FacultyID = ".$facultyID.";";
				$resultUpdateFaculty = mysql_query($sqlUpgradeFaculty, $conn) or die(mysql_error());
				if($resultUpdateFaculty)
				{
					$interactiveMessage .= "<br>The correponding record has been updated on Database.<br>
		 							You will be directed to the manage faculty page in 3 seconds.<br>
		 							Please do not perform any action on this page.";
					//TODO uncomment the following statement
					header('Refresh: 3; URL=manageFaculty.php');
				}
				else
				{
					$interactiveMessage .= "<br>there has some problems during record updating; please re-update admin. record again.<br>";
				}
			}//END ELSE($count != 0)
			
		}
		
	}//END if(isset($_POST['formSubmit']))
		
	/********************************************************************
	 * Query String variable is set
	***********************************************************************/
	if(isset($_GET['facultyID']))
	{
		/*************************************************************
		 * fetch facultyID by given faculty XML ID and parsing XML file
		*************************************************************/
		$facultyID = $_GET['facultyID'];

		$result = mysql_query("SELECT * FROM A_FACULTY WHERE FacultyID = ".$facultyID.";", $conn) or die(mysql_error());

		/*************************************************************
		 * fetch all values by given Faculty ID and parsing XML file
		*************************************************************/
		
		if(mysql_num_rows($result) != 1) {
			echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>Fatal Error:  Faculty with ID ".$facultyID." was not found!</div>";
			header("Refresh: 10; URL=manageFaculty.php");
			die();
		} else {
			//query for current info
			$row = mysql_fetch_assoc($result);
			
			$userName			= $row['UserName'];
			$userPassword		= $row['UserPassword'];
			$firstName 			= $row['FirstName'];
			$lastName			= $row['LastName'];
			$middleName 		= $row['MiddleName'];
			$email				= $row['Email'];
			$title				= $row['Title'];
			$position			= $row['Position'];
			$officeLocation		= $row['OfficeLocation'];
			$bioText			= $row['BioText'];
			$bioPhotoLink		= $row['BioPhotoLink'];
			$cvFileLink			= $row['CVFileLink'];
			$activationCode		= $row['ActivationCode'];
			$isActivated		= $row['IsActive'];
		}


	}
?>

<!-- ********************************************************************
	 * HTML
	*********************************************************************** -->
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header">Edit a Faculty Record</h1>
</div>
    
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<form id="post_form" action="editSingleFaculty.php?facultyID=<?php echo $facultyID;?>" class="form-horizontal" role="form" method="post">
	<!-- screen out interative message which is deliverd from server. -->
	<?php 
	//screen out the issues at the top of page
	if ($interactiveMessage != "")
	{
		print "<div class=\"col-sm-6 col-sm-offset-3 col-md-10 col-md-offset-2 main\">
	<div class=\"row bg-danger\">
	 		<center>".$interactiveMessage."</center><br/>
	 	</div>
	    </div>";
	}
	$interactiveMessage = "";
	?>
	<div class="form-group has-warning">
		<label for="username" class="col-sm-2 control-label">User Name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="username" placeholder="" name="username" 
				value="<?php echo $userName; ?>">
		</div>
	</div>
	<div class="form-group has-warning">
		<label for="userpassword" class="col-sm-2 control-label">User Password:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="userpassword" placeholder="" name="userpassword"
				value="<?php echo $userPassword; ?>">
		</div>
	</div>
	<div class="form-group has-warning">
		<label for="firstname" class="col-sm-2 control-label">First Name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="firstname" placeholder="" name="firstname"
				value="<?php echo $firstName; ?>">
		</div>
	</div>
	<div class="form-group has-warning">
		<label for="lastname" class="col-sm-2 control-label">Last Name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="lastname" placeholder="" name="lastname"
				value="<?php echo $lastName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="middlename" class="col-sm-2 control-label">Middle Name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="middlename" placeholder="" name="middlename"
				value="<?php echo $middleName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="email" placeholder="" name="email"
				value="<?php echo $email; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="title" class="col-sm-2 control-label">Title:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="title" placeholder="" name="title"
				value="<?php echo $title; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="position" class="col-sm-2 control-label">Position:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="position" placeholder="" name="position"
				value="<?php echo $position; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="officeLocation" class="col-sm-2 control-label">Office Location:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="officeLocation" placeholder="" name="officeLocation"
				value="<?php echo $officeLocation; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="biotext" class="col-sm-2 control-label">Bio Text:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="biotext" placeholder="" name="biotext"
				value="<?php echo $bioText; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="biophotolink" class="col-sm-2 control-label">Bio Photo Link:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="biophotolink" placeholder="" name="biophotolink"
				value="<?php echo $bioPhotoLink; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="cvfilelink" class="col-sm-2 control-label">CV File Link:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="cvfilelink" placeholder="" name="cvfilelink"
				value="<?php echo $cvFileLink; ?>">
		</div>
	</div>

	<div class="form-biotextgroup">
		<label for="isactivated" class="col-sm-2 control-label">Activated:</label>
		<div class="col-sm-6">
			<input type="radio" name="isactivated" value="YES" <?php if($isActivated == "YES"){echo 'checked';}?>>YES&nbsp;
			<input type="radio" name="isactivated" value="NO" <?php if($isActivated == "NO"){echo 'checked';}?>>NO
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-10">
			<br/>
			<!-- use input instead button -->
			<input type="button" onclick="validateEditSingleFaculty();" class='btn btn-primary' name="formSubmit" value="Update" />
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
