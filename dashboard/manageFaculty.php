<?php
	require_once("dbconnect.php");
	include_once('php/header.php');

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
				<table class="table AAA table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Middle Name</th>
							<th>User Name</th>
							<th>User Password</th>
							<th>Activate</th>
							<th>Is Active</th>
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
						
						$sql = "SELECT * FROM A_FACULTY;";
						$result = mysql_query($sql, $conn) or die(mysql_error());
						while($row = mysql_fetch_assoc($result))
						{
							$i++;
							print <<<HERE
							<tr>
								<td>$i</td>
								<td>{$row['FirstName']}</td>
								<td>{$row['LastName']}</td>
								<td>{$row['MiddleName']}</td>
								<td>{$row['UserName']}</td>
								<td>{$row['UserPassword']}</td>
								<td><button type="button" class="btn btn-primary btn-xs" onclick="window.location='validate.php?theQueryString={$row['ActivationCode']}'">Verify</td>
								<td>{$row['IsActive']}</td>
								<td>{$row['FirstAccessDate']}</td>
								<td>{$row['LastAccessDate']}</td>
								<td><button type="button" class="btn btn-primary btn-xs" onclick="window.location='editSingleFaculty.php?facultyID={$row['FacultyID']}'">Edit</td></td>
							</tr>
HERE;
						
						}
	echo "</tbody></table></div></div></div></div>";
						
	include_once('php/footer.php');
						
?>