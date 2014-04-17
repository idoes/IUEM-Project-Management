<?php
	require_once "dbconnect.php";
	require_once "php/header.php";
	
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

echo <<<EOT
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <!-- TODO use session variable to get user's full name -->
      <h1 class="page-header">Current Admins</h1>
 </div>
    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table table-bordered AAA">
					<thead>
						<tr>
							<th>#</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Middle Name</th>
							<th>User Name</th>
							<th>User Password</th>
							<th>Activate</th>
							<th>Active</th>
							<th>First Access Date</th>
							<th>Last Access Date</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
EOT;
						//TODO
						//Add for loop to go through user DB and print out tr/td info
						$i = 0;
						while($row = mysql_fetch_assoc($result))
						{
							//get the index for this associated array
							$i++;
							//prepare for query string
							print <<<HERE
							<tr>
								<td>{$row['AdminID']}</td>
								<td>{$row['FirstName']}</td>
								<td>{$row['LastName']}</td>
								<td>{$row['MiddleName']}</td>
								<td>{$row['Email']}</td>
								<td>{$row['Password']}</td>
								<td><button type="button" class="btn btn-primary btn-xs" onclick="window.location='validate.php?theQueryString={$row['ActivationCode']}'">Verify</td></td>
								<td>{$row['IsActive']}</td>
								<td>{$row['FirstAccessDate']}</td>
								<td>{$row['LastAccessDate']}</td>
								<td><button type="button" class="btn btn-primary btn-xs" onclick="window.location='editSingleAdmin.php?adminID={$row['AdminID']}'">Edit</td>
							</tr>
HERE;
							//test
							//echo "<br>" .$i;
						}//end foreach ($adminRecord as $index)
echo <<<EOT
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
EOT;

	require_once "php/footer.php";


?>