<?php
	include_once('dbconnect.php');
	include_once('php/header.php');
echo <<<EOT
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Manage Projects</h1>
 </div>    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table AAA table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Project Title</th>
							<th>Created On</th>
							<th>PI</th>
							<th>View Project</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
EOT;
	//TODO
	//Add for loop to go through project DB and print out tr/td info
	if($_SESSION['ACCESS_LEVEL'] === "FACULTY") {
		$result = mysql_query(" SELECT ProjectID FROM A_RESEARCH WHERE FacultyID = ".$_SESSION['UID'], $conn) or die(mysql_error());
		$has_found = false;
		$projects_part_of = array();
		$num_projects_part_of = mysql_num_rows($result);
		$i=0;
		while($row = mysql_fetch_assoc($result)){
			$projects_part_of[$i] = $row['ProjectID'];
			$i++;
			$has_found = true;
		}
		
		$result = mysql_query(" SELECT ProjectID FROM A_MANAGEMENT WHERE FacultyID = ".$_SESSION['UID'], $conn) or die(mysql_error());
		
		$num_projects_part_of = mysql_num_rows($result);
		
		while($row = mysql_fetch_assoc($result)){
			$projects_part_of[$i] = $row['ProjectID'];
			$i++;
			$has_found = true;
		}
		
		$sql = " SELECT * FROM A_PROJECT WHERE";
		
		for($i = 0; $i < count($projects_part_of); $i++) {
			if($i!=(count($projects_part_of)-1))
			{
				$sql.= " ProjectID = " .$projects_part_of[$i]. " OR";
			} else {
				$sql.= " ProjectID = " .$projects_part_of[$i]. ";";
			}
		}
		
		if($has_found) {$result = mysql_query($sql, $conn) or die(mysql_error()); }
		
	} else {
		$result = mysql_query(" SELECT *
								FROM A_PROJECT;", $conn) or die(mysql_error());
	}
		

	$num_rows = mysql_num_rows($result);
	
	$i = 1;

	while($row = mysql_fetch_assoc($result))
	{
		$project_id = $row['ProjectID'];
		echo "<tr><td>".$i."</td>";
		echo "<td>".$row['Title']."</td>";
		echo "<td>".$row['InitialDate']."</td>";
		$user_query = mysql_query(" SELECT FacultyID
									from A_MANAGEMENT
									WHERE ProjectID = '".$project_id."'
									AND Responsibility='PI' LIMIT 1;");
											
		if(mysql_num_rows($user_query) == 0)
		{
			$user_query=array();
			$user_query['FacultyID'] = 'PI Not Set';
		} else {
			$user_query = mysql_fetch_array($user_query);
	                $faculty_query = mysql_query( " SELECT FirstName, LastName
                                                        FROM A_FACULTY
                                                        WHERE FacultyID = '".$user_query['FacultyID']."'
                                                        LIMIT 1;" );
                                                                                
	                $faculty_query = mysql_fetch_array($faculty_query);
		}

		if($user_query['FacultyID'] !== 'PI Not Set')
		{
			echo "<td>".$faculty_query['FirstName']." ".$faculty_query['LastName']."</td>";
		} else {
			echo "<td>PI Not Set</td>";	
		}
		echo "<td><button type='button' class='btn btn-primary btn-xs' onclick=window.location='viewProject.php?projectID=$project_id'>View Project</button></td>"; 
		echo "<td><button type='button' class='btn btn-danger btn-xs' onclick=window.location='deleteProject.php?projectID=$project_id'>Delete Project</button></td>";
		$i++;
	}
	

echo <<<EOT
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
EOT;

	include_once('php/footer.php');

?>