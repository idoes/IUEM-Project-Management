<?php
	include_once('./php/header.php');
	require_once "dbconnect.php";
	
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
		//$AdminInstance->addChild('ActivationCode', $row['ActivationCode']);
		//$AdminInstance->addChild('IsActive', $row['IsActive']);
	
	}//end while ($row = mysql_fetch_assoc($result))
	
	/*************************************************************
	 * write $xml variable to a file on the server
	*************************************************************/
	$fp = fopen("xml/Table-Solo-Admin.xml","wb");
	fwrite($fp, $xml->asXML());
	fclose($fp);
?>
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
					<?php
						
						//TODO
						//Add for loop to go through user DB and print out tr/td info
						$adminRecord = simplexml_load_file('xml/Table-Solo-Admin.xml');
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
					?>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
	include_once('./php/footer.php');
?>
