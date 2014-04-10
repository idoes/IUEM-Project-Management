<?php
	include_once('dbconnect.php');
	include_once('php/header.php');
	$table_content = "";
	if($_SESSION['ACCESS_LEVEL'] === 'SUPER_ADMIN')
	{
		$result = mysql_query(" SELECT *
								FROM A_ADMIN
								WHERE AdminID = ".$_SESSION['UID'].";", $conn);
																
		$result = mysql_fetch_array($result);
		
  		$table_content.="<tr><td><b>Email:</b></td>
						  <td>".$result['Email']."</td>
						  </tr>
						  <tr>
						  <td><b>First Access Date:</b></td>
						  <td>".$result['FirstAccessDate']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Access Date:</b></td>
						  <td>".$result['LastAccessDate']."</td>
						  </tr>
						  <tr>
						  <td><b>First Name:</b></td>
						  <td>".$result['FirstName']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Name:</b></td>
						  <td>".$result['LastName']."</td>
						  </tr>
						  <tr>
						  <td><b>Middle Name:</b></td>
						  <td>".$result['MiddleName']."</td>
						 </tr>";
		
	} else if($_SESSION['ACCESS_LEVEL'] === 'FACULTY') {
		$result = mysql_query(" SELECT *
								FROM A_FACULTY
								WHERE FacultyID = ".$_SESSION['UID'].";", $conn);
																
		$result = mysql_fetch_array($result);
  		$table_content.="<tr><td><b>First Name:</b></td>
						  <td>".$result['FirstName']."</td>
						  </tr>
						  <tr>
						  <td><b>Last Name:</b></td>
						  <td>".$result['LastName']."</td>
						  </tr>
						  <tr>
						  <td><b>Middle Name::</b></td>
						  <td>".$result['MiddleName']."</td>
						  </tr>
						  <tr>
						  <td><b>Email:</b></td>
						  <td>".$result['UserName']."</td>
						  </tr>
						  <tr>
						  <td><b>First Access Date:</b></td>
						  <td>".$result['FirstAccessDate']."</td>
						  </tr>
						  <!--tr>
						  <td><b></b></td>
						  <!--TODO: Add here implementation for lastaccessdate update it on login-->
						  <td></td>
						 </tr-->";
	}
	
echo <<<EOT
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h1 class="page-header">My Profile</h1>
</div>

<div class="col-sm-9 col-sm-offset-3 col-md-5 col-md-offset-2 main">
	<table class="table">
  		{$table_content}
	</table>
	<!-- The following need to be delete after PHP implementation. -->
	<div class="col-sm-offset-0 col-sm-10">
	<br/>
		<!--button type="submit" class="btn btn-primary btn-lg">
			Edit My Profile
		</button-->
	</div>	
</div>
EOT;
	include_once('php/footer.php');

?>