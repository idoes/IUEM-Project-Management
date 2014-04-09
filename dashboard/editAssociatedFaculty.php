<?php
	include_once('dbconnect.php');
	include_once('./php/header.php');	
	$projectID = $_GET['projectID'];

	//get current faculty associated with this project
	$result = mysql_query("SELECT FacultyID,ResearchStartDate  FROM `A_RESEARCH` WHERE ProjectID = '$projectID';", $conn) or die(mysql_error());

	//if some, display in table
	
	if(mysql_num_rows($result) !=0)
	{
		//table header definition
		echo <<<EOT
<div class="container-fluid">
<div class="row">
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<table class="table table-condensed">
<thead>
	<th>#</th>
	<th>Faculty Name</th>
	<th>Added to Project</th>
	<th>Remove</th>
</thead>
<tbody>
EOT;
	} else {
		echo <<<EOT
<div class="container-fluid">
<div class="row">
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

		No faculty are a part of this project!
	</div></div></div>
EOT;
	}
	$i = 0;
	while($row = mysql_fetch_assoc($result))
	{
		$i++;	
		echo "<td>$i</td>";

		//fetch first and last name
		$nameQuery = mysql_query("SELECT FirstName, LastName from `A_FACULTY` WHERE FacultyID = ".$row['FacultyID'].";") or die(mysql_error());		

		$nameQuery = mysql_fetch_assoc($nameQuery);

		echo "<td>".$nameQuery['FirstName']." ".$nameQuery['LastName']."</td>";
		echo "<td>".$row['ResearchStartDate']."</td>";
		echo "<td><button type='button' class='btn btn-danger' onclick=\"window.location='removeFacultyRelation.php?projectID=".$projectID."'\">Remove From Project</button>";
	}
	echo "</tbody></table></div></div></div>";
	include_once('./php/footer.php');
?>
