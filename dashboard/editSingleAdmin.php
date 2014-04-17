<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	require_once "dbconnect.php";
	
	//TODO
	/*************************************************************
	 * DB activity - Check SESSION vaiable against DB.ADMIN
	*************************************************************/
	
	
	

	$interactiveMessage = "";
	$adminID 		= "";
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
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		//test
		//echo "<br>This is After Submit";
		$adminID = $_GET['adminID'];
		$userName			= mysql_escape_string(trim($_POST['username']));
		$userPassword		= mysql_escape_string(trim($_POST['userpassword']));
		$firstName 			= mysql_escape_string(trim($_POST['firstname']));
		$lastName			= mysql_escape_string(trim($_POST['lastname']));
		$middleName 		= mysql_escape_string(trim($_POST['middlename']));
		//$activationCode		= trim($_POST['activationcode']);
		$isActivated		= mysql_escape_string(trim($_POST['isactivated']));
		
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
			$sqlCountAdmin = "SELECT COUNT(*) AS COUNTER FROM A_ADMIN WHERE AdminID = ".$_GET['adminID'].";";
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
				IsActive		= '$isActivated'
				WHERE AdminID = ".$_GET['adminID'];
				//test
				//echo "<br>" . $sqlUpgradeAdmin;
				$resultUpdateAdmin = mysql_query($sqlUpgradeAdmin, $conn) or die(mysql_error());
				if($resultUpdateAdmin)
				{
					$interactiveMessage .= "The correponding record has been updated on Database.<br/>
		 							You will be directed to the dashboard in 3 seconds.<br/>
		 							Please do not perform any actions on this page.<br/>";
					//TODO uncomment the following statement
					header('Refresh: 3; URL=manageAdmins.php');
				}
				else
				{
					$interactiveMessage .= "there has some problems during record updating; please re-update admin. record again.<br>";
				}
			}
			
			
			
			
		}// END if ($firstNameIsOk && $lastNameIsOk && $passwordIsOk && $emailIsOk)
		
		
		
		
	}//END if(isset($_POST['formSubmit']))
	
	if($_SERVER['REQUEST_METHOD'] === 'GET')
	{
		$adminID = $_GET['adminID'];
		$result = mysql_query("SELECT * FROM A_ADMIN WHERE AdminID = ".$adminID.";", $conn) or die(mysql_error());
		
		if(mysql_num_rows($result) != 1)
		{
			echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>Fatal Error:  No Admin with ID ".$adminID."!</div>";
			header("Refresh: 10; URL=manageAdmins.php");
			die();
		} else {
			$row = mysql_fetch_assoc($result);
			
			$firstName = $row['FirstName'];
			$lastName = $row['LastName'];
			$middleName = $row['MiddleName'];
			$userName = $row['Email'];
			$userPassword = $row['Password'];
			$isActivated = $row['IsActive'];
		}
	}



?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header">Edit an Administrator</h1>
</div>

	<?php 
	//screen out the issues at the top of page
	if ($interactiveMessage != "")
	{
		print "<div class=\"col-sm-6 col-sm-offset-3 col-md-10 col-md-offset-2 main\">
	<div class=\"row bg-danger\">
	 		<center><br/>".$interactiveMessage."<br/></center>
	 	</div>
	    </div>";
	}
	$interactiveMessage = "";
	?>
    
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<form id="post_form" action="editSingleAdmin.php?adminID=<?php echo $adminID;?>" class="form-horizontal" role="form" method="post">
	<!-- screen out interative message which is deliverd from server. -->

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
	<div class="form-group">
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
			<input type="button" onclick="validateEditSingleAdmin();" class='btn btn-primary' name="formSubmit" value="Update" />
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