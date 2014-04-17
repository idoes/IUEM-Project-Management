<?php
	include_once('./php/header.php');
	include_once('dbconnect.php');
	
	//query database for the current project
	$project_id = $_GET['projectID'] or die('No project ID set...');

	//WE NEED 5 queries
	//query 1:  request project information from A PROJECT
	//query 2:  get PI relationship from A MANAGEMENT
	//query 3:  get CO-Pi reltionship from A MANAGEMENT
	//query 4:  get faculty relationship from A_RESEARCH
	//query 5:  get Files from A_ATTACHMENT
	
	//define all variables
	$project_title = "";
	$project_abstract = "";
	$project_initial_date = "";
	$project_description = "";
	$project_close_date = "";
	$pi_first_name = "";
	$pi_last_name = "";
	$co_pi_array = array();
	$faculty_array = array();
	$file_array = array();
	
	//query 1:  request project information from A PROJECT
	$result = mysql_query(" SELECT * FROM A_PROJECT WHERE ProjectID = ".$project_id.";", $conn) or die(mysql_error());
	//check to make sure this project exists
	if(mysql_num_rows($result) != 1)
	{
		echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>Fatal Error:  Project with ID ".$project_id." was not found!</div>";
		header("Refresh: 10; URL=manageProjects.php");
		die();
	} else {
		//fetch assoc array
		$result = mysql_fetch_assoc($result);
		
		//set variables for use in the table
		$project_title = $result['Title'];
		$project_abstract = $result['Abstract'];
		$project_initial_date = $result['InitialDate'];
		$project_description = $result['Description'];
		$project_close_date = $result['CloseDate'];
	}
	
	//query 2:  get PI relationship from A MANAGEMENT
	$result = mysql_query(" SELECT FacultyID FROM A_MANAGEMENT WHERE ProjectID = ".$project_id." AND Responsibility = 'PI';", $conn) or die(mysql_error());
	
	//make sure there is a result and that it's only 1
	if(mysql_num_rows($result) != 1)
	{
		echo "<div class='col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main'>Fatal Error:  No PI has been setup for project ".$project_id." (".$project_title.")!  A DBA will need to manually create or repair this relationship.</div>";
		header("Refresh: 10; URL=manageProjects.php");
		die();
	} else {
		//fetch assoc array
		$result = mysql_fetch_assoc($result);
		
		$temp_pi_faculty_id = $result['FacultyID'];
		
		//query for the pi's first/last name
		$result = mysql_query(" SELECT FirstName, LastName FROM A_FACULTY WHERE FacultyID = ".$temp_pi_faculty_id.";", $conn) or die(mysql_error());
		
		//get assoc array
		$result = mysql_fetch_array($result);
		
		//set variables for use in the table
		$pi_first_name = $result['FirstName'];
		$pi_last_name = $result['LastName'];		
	}
	
	//query 3:  get CO-Pi reltionship from A MANAGEMENT
	$result = mysql_query(" SELECT FacultyID FROM A_MANAGEMENT WHERE ProjectID = ".$project_id." AND Responsibility = 'CO-PI';", $conn) or die(mysql_error());
	$num_co_pis = mysql_num_rows($result);
	
	//make sure there is at least one
	if($num_co_pis == 0)
	{
		//die("Fatal Error:  No PI has been setup for project ".$project_id." (".$project_title.")!  A DBA will need to manually create or repair this relationship.");
		//header("Refresh: 10; URL=manageProjects.php");
		//TODO:  DRAW BLANK TABLE FOR CO-PIS
	} else {
		//get the number of relationships
		

		for($i = 0; $i < $num_co_pis; $i++)
		{
			//fetch assoc array
			$row = mysql_fetch_assoc($result);
				
			$temp_co_pi_faculty_id = $row['FacultyID'];
			
			//query for the pi's first/last name
			$row = mysql_query(" SELECT FirstName, LastName FROM A_FACULTY WHERE FacultyID = ".$temp_co_pi_faculty_id.";", $conn) or die(mysql_error());
			
			//get assoc array
			$row = mysql_fetch_array($row);
			
			//set variables for use in the table
			$co_pi_array[$i] = $row['FirstName'] ." ". $row['LastName'];
		}	
		
		
	}
	
	//query 4:  get faculty relationship from A_RESEARCH
	$result = mysql_query(" SELECT FacultyID FROM A_RESEARCH WHERE ProjectID = ".$project_id.";", $conn) or die(mysql_error());
	$num_faculty = mysql_num_rows($result);
	//make sure there is at least one
	if($num_faculty == 0)
	{
		//die("Fatal Error:  No PI has been setup for project ".$project_id." (".$project_title.")!  A DBA will need to manually create or repair this relationship.");
		//header("Refresh: 10; URL=manageProjects.php");
		//TODO:  DRAW BLANK TABLE FACULTY
	} else {
		//get the number of relationships
		echo $num_faculty;
		for($i = 0; $i < $num_faculty; $i++)
		{
			//fetch assoc array
			$row = mysql_fetch_assoc($result);
				
			$temp_faculty_id = $row['FacultyID'];
			
			//query for the pi's first/last name
			$row = mysql_query(" SELECT FirstName, LastName FROM A_FACULTY WHERE FacultyID = ".$temp_faculty_id.";", $conn) or die(mysql_error());
			
			//get assoc array
			$row = mysql_fetch_array($row);
			
			//set variables for use in the table
			$faculty_array[$i] = $row['FirstName'] ." ". $row['LastName'];
		}	
	}	
	
	//query 5:  get Files from A_ATTACHMENT	
	$result = mysql_query("SELECT * FROM A_ATTACHMENT WHERE ProjectID =".$project_id.";", $conn) or die (mysql_error());
	$num_files = mysql_num_rows($result);
	
	//make sure there are some files
	if($num_files == 0)
	{
		//if no files, do this
		//echo "No files have been uploaded to this project!<br/>";
	} else {
		//else, do this
		for($i = 0; $i < $num_files; $i++)
		{
			$row = mysql_fetch_assoc($result);
				
			$file_array[$i] = $row['ItemServerLink'];
		}
	}
		
	// while($row = mysql_fetch_assoc($resultQeryFive2))
	// {
		// echo "
		// <tr>
			// <td>".$i."</td>";
			// echo "<td>".$project_title."</td>";
			// echo "<td>".basename($row['ItemServerLink'])."</td>";
			// echo "<td>
			// <button type='button' class='btn btn-primary btn-xs' onclick=window.location='".$row['ItemServerLink']."'>
				// Download
			// </button></td>";
			// echo "<td>
			// <button type='button' class='btn btn-danger btn-xs' onclick=window.location='deleteFile.php?attachmentID=".$row['AttachmentID']."'>
				// Delete
			// </button></td>";
			// echo "
		// </tr>";
		// $i++;
	// }
	
	//WRITE ALL INFORMATION GATHERED INTO TABLES:
	//Write out page header
echo <<<EOT
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<h1 class="page-header">Viewing Project '{$project_title}'</h1>
</div>
    
<div class="col-sm-2 col-sm-offset-3 col-md-10 col-md-offset-2 main">
EOT;

	//start writing table displaying basic project information:
	// $project_title = $result['Title'];
	// $project_abstract = $result['Abstract'];
	// $project_initial_date = $result['InitialDate'];
	// $project_description = $result['Description'];
	// $project_close_date = $result['CloseDate'];
echo <<<EOT
<div class="highlight">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td>Project Title:</td>
				<td>$project_title</td>
			</tr>
			<tr>
				<td>Project Abstract:</td>
				<td>$project_abstract</td>
			</tr>
			<tr>
				<td>Start Date:</td>
				<td>$project_initial_date</td>
			</tr>
			<tr>
				<td>Description:</td>
				<td>$project_description</td>
			</tr>
			<tr>
				<td>Close Date:</td>
				<td>$project_close_date</td>
			</tr>			
			<tr>
				<td>Project Investigator</td>
				<td>$pi_first_name $pi_last_name</td>
			</tr>
		</tbody>
	</table>
	<button type='button' class='btn btn-primary' onclick=window.location='editSingleProject.php?projectID=$project_id'>Edit Project Details</button><br/><br/>
	</div>
EOT;
	//echo out the co_pi_array
	if(count($co_pi_array) != 0)
	{
		echo <<<EOT
<div class="highlight">
	<table class="table table-bordered">
		<thead>
			<th>#</th>
			<th>CO-PI Name</th>
		</thead>
		<tbody>
EOT;
		for($i = 0; $i < count($co_pi_array); $i++)
		{
			echo "<tr>
					<td>".($i+1)."</td>
					<td>";
			echo $co_pi_array[$i];
			echo 	"</td></tr>";
		}
echo <<<EOT
</tbody>
	</table>
EOT;

	} else {
		echo "<div class='highlight'>No CO-PI Relationships found for this project!<br/><br/>";
	}
echo <<<EOT
	<button type='button' class='btn btn-primary' onclick=window.location='editAssociatedCOPI.php?projectID=$project_id'>Edit CO-PI's</button><br/><br/>
</div>
EOT;
	//echo out the faculty_array
	if(count($faculty_array) != 0)
	{
		echo <<<EOT
<div class='highlight'>
	<table class="table table-bordered">
		<thead>
			<th>#</th>
			<th>Faculty Name</th>
		</thead>
		<tbody>
EOT;
		for($i = 0; $i < count($faculty_array); $i++)
		{
			echo "<tr>
					<td>".($i+1)."</td>
					<td>";
			echo $faculty_array[$i];
			echo 	"</td></tr>";
		}
	} else {
		echo "<div class='highlight'>No Faculty Relationships found for this project!<br/><br/>";
	}
echo <<<EOT
		</tbody>
	</table>
	<button type='button' class='btn btn-primary' onclick=window.location='editAssociatedFaculty.php?projectID=$project_id'>Edit Faculty</button><br/><br/>
</div>
EOT;

	//echo out file array
//echo out the faculty_array
	if(count($file_array) != 0)
	{
		echo <<<EOT
<div class='highlight'>
	<table class="table table-bordered">
		<thead>
			<th>#</th>
			<th>File Name</th>
			<th>File Download</th>
		</thead>
		<tbody>
EOT;
		for($i = 0; $i < count($file_array); $i++)
		{
			echo "<tr>
					<td>".($i+1)."</td>
					<td>".basename($file_array[$i])."</td>
					<td><a href='getFile.php?dir=$file_array[$i]'>Download</a></td></tr>";
		}
	} else {
		echo "<div class='highlight'>No files found for this project!<br/><br/>";
	}
echo <<<EOT
		</tbody>
	</table>
	<button type='button' class='btn btn-primary' onclick=window.location='fileUpload.php?projectID=$project_id'>Edit Files</button><br/><br/>
</div>
EOT;
	include_once('./php/footer.php');
?>
