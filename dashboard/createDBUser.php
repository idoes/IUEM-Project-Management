<?php
	include_once('./php/header.php');
	include_once('./inc/utilityFunction.php');
	
	/*
	 *  trim and validation
	 */
	function checkInput($formInputArray)
	{
		foreach($formInputArray as $key => $value)
		{
			
		}
	}
	
	
?>   
<?php 
	//always initialized variables to be used
	$interactiveMessage = "";
	$firstName 			= "";
	$lastName			= "";
	$middleName 		= "";
	$password 			= "";
	$email				= "";

	if(isset($_POST['formSubmit']))
	{
		//take the information submitted and verify inputs
		//always trim the user input to get rid of the additiona white spaces on both ends of the user input
		$firstName 	= $_POST['firstname'];
		$lastName	= $_POST['lastname'];
		$middleName = $_POST['middlename'];
		$password 	= $_POST['password'];	
		$email		= $_POST['email'];
	
/*	
		$formInputArray = array (
				'firstName' 	=> array('field' => 'TextBox[First Name]', 'value' 	=> $firstName),
				'middleName' 	=> array('field' => 'TextBox[last Name]',  'value' 	=> $middleName),
				'lastName' 		=> array('field' => 'TextBox[last Name]',  'value' 	=> $firstName),
				'password' 		=> array('field' => 'TextBox[Password]',   'value' 	=> $password),
				'email' 		=> array('field' => 'TextBox[Email]',      'value'	=> $email)
		);
*/	
	
	}//end if(isset($_POST['formSubmit']))




    
?>    

    
    
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Database User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createDBUser.php" class="form-horizontal" role="form" method="post">
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
					<input type="text" class="form-control" id="password" placeholder="Password" name="password">
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
					<button type="submit" class="btn btn-primary" name="formSubmit">
						Create Database User
					</button>
				</div>
			</div>
	</form>
    </div>

	</div>

<?php
	include_once('./php/footer.php');
?>