<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	require_once "dbconnect.php";
	
	//TODO
	/*************************************************************
	 * DB activity - Check SESSION vaiable against DB.ADMIN
	*************************************************************/
	
	
	

	$interactiveMessage = "";
	$adminXMLID 		= "";
	
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

	

?>
<?php 
	if(isset($_POST['formSubmit']))
	{
		//test
		//echo "<br>This is After Submit";
		
		$userName			= trim($_POST['username']);
		$userPassword		= trim($_POST['userpassword']);
		$firstName 			= trim($_POST['firstname']);
		$lastName			= trim($_POST['lastname']);
		$middleName 		= trim($_POST['middlename']);
		$activationCode		= trim($_POST['activationcode']);
		$isActivated		= trim($_POST['isactivated']);
		
		//TODO 
		/*************************************************************
		 * Input validation
		*************************************************************/
		$firstNameIsOk 	= false;
		$lastNameIsOk	= false;
		$middleNameIsOk = false;
		$passwordIsOk 	= false;
		$emailIsOk		= false;
		
		// validate text box input 1 - check empty
		if(empty($firstName))
		{
			$interactiveMessage = $interactiveMessage . "First Name can not be empty.<br>";
		}
		if(empty($lastName))
		{
			$interactiveMessage = $interactiveMessage . "Last Name can not be empty.<br>";
		}
		if(empty($userPassword))
		{
			$interactiveMessage = $interactiveMessage . "User Password can not be empty.<br>";
		}
		if(empty($userName))
		{
			$interactiveMessage = $interactiveMessage . "User Name can not be empty.<br>";
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
		if(pwdValidate($userPassword) == false)
		{
			$interactiveMessage = $interactiveMessage . "User Password should be Longer than 12 characters and alphanumeric letters.<br>";
		}
		else
		{
			$passwordIsOk = true;
		}
		
		//validate text box input 4 - Email qualify
		if(emailAddressCheck($userName) == false)
		{
			$interactiveMessage = $interactiveMessage . "User Name is not an valid Email Address.<br>";
		}
		else
		{
			$emailIsOk = true;
		}
		
		// Checking passed
		if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
		{
			
			/*************************************************************
			 * DB activity - Ensure User name is unique
			*************************************************************/
			$sqlCountAdmin = "SELECT COUNT(*) AS COUNTER FROM A_ADMIN WHERE Email = '" . $userName. "';";
			$result = mysql_query($sqlCountAdmin, $conn) or die(mysql_error());
			$field = mysql_fetch_object($result);
			$count = $field -> COUNTER;

			if($count != 1) //change this because there should always be one user with this email, just not more
			{
				$interactiveMessage .= "<br>User name value is duplicated with others user name.<br>";
			}
			else
			{
				//TODO
				/*************************************************************
				 * DB activity - update DB.Admin by providing admin ID
				*************************************************************/
					
				$sqlUpgradeAdmin = "UPDATE A_ADMIN
				SET
				Email			= '$userName',
				Password		= '$userPassword',
				FirstName 		= '$firstName',
				LastName		= '$lastName',
				MiddleName 		= '$middleName',
				ActivationCode	= '$activationCode',
				IsActive		= '$isActivated'
				WHERE Email = '$userName';";
				//test
				//echo "<br>" . $sqlUpgradeAdmin;
				$resultUpdateAdmin = mysql_query($sqlUpgradeAdmin, $conn) or die(mysql_error());
				if($resultUpdateAdmin)
				{
					$interactiveMessage .= "The correponding record has been updated on Database.<br/>
		 							You will be directed to the dashboard in 3 seconds.<br/>
		 							Please do not perform any actions on this page.<br/><br/><br/>";
					//TODO uncomment the following statement
					header('Refresh: 3; URL=dashboard.php?redirect=manage-admins');
				}
				else
				{
					$interactiveMessage .= "there has some problems during record updating; please re-update admin. record again.<br>";
				}
			}
			
			
			
			
		}// END if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
		
		
		
		
	}//END if(isset($_POST['formSubmit']))
	
	if(isset($_GET['AdminXMLID']))
	{
		/*************************************************************
		 * fetch adminID by given admin XML ID and parsing XML file
		*************************************************************/
		$adminXMLID = $_GET['AdminXMLID'];
		
		//test
		// echo "<br>This is First time load";
		// echo "<br>Query String[AdminXMLID]: " . $_GET['AdminXMLID'];
		// echo "<br>Before assign value from XML";
		// echo "<br>Input 1: " . $adminXMLID;
		// echo "<br>Input 2: " . $adminID;
		// echo "<br>Input 3: " . $userName;
		// echo "<br>Input 4: " . $userPassword;
		// echo "<br>Input 5: " . $firstAccessDate;
		// echo "<br>Input 6: " . $lastAccessDate;
		// echo "<br>Input 7: " . $firstName;
		// echo "<br>Input 8: " . $lastName;
		// echo "<br>Input 9: " . $middleName;
		// echo "<br>Input 10: " . $activationCode;
		// echo "<br>Input 11: " . $isActivated;
		
		

		$adminRecord = simplexml_load_file('xml/Table-Solo-Admin.xml');
		for($i = 0; $i < count($adminRecord); $i++)
		{
			if($i == $adminXMLID)
			{
				$adminID = $adminRecord->AdminInstance[$i]->AdminID;
			}
		}
		/*
		$i = -1;
		foreach ($adminRecord as $index)
		{
			$i ++;
			if ($index == $adminXMLID)
			{
				$adminID = $index->AdminID;
			}
		}//END foreach ($adminRecord as $index)
		*/
		/*************************************************************
		 * fetch all values by given admin ID and parsing XML file
		*************************************************************/
		foreach ($adminRecord as $index)
		{
			if ($index == $adminID)
			{
				$userName			= $index->Email;
				$userPassword		= $index->Password;
				$firstAccessDate	= $index->FirstAccessDate;
				$lastAccessDate 	= $index->LastAccessDate;
				$firstName 			= $index->FirstName;
				$lastName			= $index->LastName;
				$middleName 		= $index->MiddleName;
				$activationCode		= $index->ActivationCode;
				$isActivated		= $index->IsActive;
			}
		}//END foreach ($adminRecord as $index)
			
		
		//test
		// echo "<br>After assign value from XML";
		// echo "<br>Input 1: " . $adminXMLID;
		// echo "<br>Input 2: " . $adminID;
		// echo "<br>Input 3: " . $userName;
		// echo "<br>Input 4: " . $userPassword;
		// echo "<br>Input 5: " . $firstAccessDate;
		// echo "<br>Input 6: " . $lastAccessDate;
		// echo "<br>Input 7: " . $firstName;
		// echo "<br>Input 8: " . $lastName;
		// echo "<br>Input 9: " . $middleName;
		// echo "<br>Input 10: " . $activationCode;
		// echo "<br>Input 11: " . $isActivated;
	}



?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header">Edit an Administrator</h1>
</div>
    
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<form action="editSingleAdmin.php" class="form-horizontal" role="form" method="post">
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
			<input type="text" class="form-control" id="firstname" placeholder="" name="firstname" 
				value="<?php echo $firstName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-1 control-label">Last Name:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="lastname" placeholder="" name="lastname"
				value="<?php echo $lastName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="middlename" class="col-sm-1 control-label">Middle Name:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="middlename" placeholder="" name="middlename"
				value="<?php echo $middleName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-sm-1 control-label">User Name:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="username" placeholder="" name="username"
				value="<?php echo $userName; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="userpassword" class="col-sm-1 control-label">User Password:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="userpassword" placeholder="" name="userpassword"
				value="<?php echo $userPassword; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="activationcode" class="col-sm-1 control-label">Activation Code:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="activationcode" placeholder="" name="activationcode"
				value="<?php echo $activationCode; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="isactivated" class="col-sm-1 control-label">Activated:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="isactivated" placeholder="" name="isactivated"
				value="<?php echo $isActivated; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="firstaccessdate" class="col-sm-1 control-label">First Access Date:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="firstaccessdate" placeholder="" name="firstaccessdate"
				value="<?php echo $firstAccessDate; ?>" disabled>
		</div>
	</div>
	<div class="form-group">
		<label for="lastaccessdate" class="col-sm-1 control-label">Last Access Date:</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="lastaccessdate" placeholder="" name="lastaccessdate"
				value="<?php echo $lastAccessDate; ?>" disabled>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-10">
			<br/>
			<!-- use input instead button -->
			<input type="submit" class='btn btn-primary' name="formSubmit" value="Update" />
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