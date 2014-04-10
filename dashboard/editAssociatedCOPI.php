<?php
include_once ('dbconnect.php');
include_once ('./php/header.php');
$projectID = $_GET['projectID'];

//get current faculty associated with this project
$result = mysql_query("SELECT FacultyID,ManageStartDate  FROM `A_MANAGEMENT` WHERE ProjectID = '$projectID';", $conn) or die(mysql_error());

//if some, display in table

if (mysql_num_rows($result) != 0) {
	//table header definition
	echo <<<EOT
        <div class="container-fluid">
              <div class="row">
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                  <h1 class="page-header">Current CO-PI Working on this Project:</h1>
                </div>
              </div>
            
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

		No CO-PI are a part of this project!
	</div></div></div>
EOT;
}
$i = 0;
while ($row = mysql_fetch_assoc($result)) {
	$i++;
	echo "<tr><td>$i</td>";

	//fetch first and last name
	$nameQuery = mysql_query("SELECT FirstName, LastName from `A_FACULTY` WHERE FacultyID = " . $row['FacultyID'] . ";") or die(mysql_error());

	$nameQuery = mysql_fetch_assoc($nameQuery);

	echo "<td>" . $nameQuery['FirstName'] . " " . $nameQuery['LastName'] . "</td>";
	echo "<td>" . $row['ManageStartDate'] . "</td>";
	echo "<td><button type='button' class='btn btn-danger' onclick=\"window.location='removeCOPIRelation.php?projectID=" . $projectID . "&facultyID=" . $row['FacultyID'] . "'\">Remove From Project</button></td><tr>";
}

echo "</tbody></table></div></div>";

echo <<<EOT
	<div class="container-fluid">
	      <div class="row">	        
	        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	          <h1 class="page-header">Add CO-PI to this Project:</h1>
	        </div>
	      </div>
                       <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                        <form action="addAssociatedCOPI.php" class="form-horizontal" role="form" method="get">
                                <div class="form-group">
                                        <label for="projectInspector" class="col-sm-1 control-label">Name:</label>
                                        <div class="col-sm-4">
                                                <input autocomplete="off" value="" type="text" list="txtHint" class="form-control" id="projectInspector" placeholder="CO-PI Name" name="facultyName" onkeyup="showHint(this.value)">
                                                <datalist id="txtHint"></datalist>
                                        </div>
                                </div>
			<input type="hidden" value='$projectID' name='projectID'></input>
			<div class="form-group">
<br/><br/>
			<button action="submit" class="btn btn-primary">Add</button>
			</div>                        
</form>
</div>
</div>
</div>


EOT;
include_once ('./php/footer.php');
?>
