<?php
	require_once("php/header.php");
		echo <<<EOT
	   <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	      <h1 class="page-header">Create a Project</h1>
	    </div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<form action="createProjectPost.php" class="form-horizontal" role="form" method="post">
				<div class="form-group">
					<label for="projecttitle" class="col-sm-2 control-label">Title:</label>
					<div class="col-sm-6">
						<input value="" type="text" class="form-control" id="projecttitle" placeholder="Project Title" name="projecttitle">
					</div>
				</div>
				<div class="form-group">
					<label for="projecttitle" class="col-sm-2 control-label">Abstract:</label>
					<div class="col-sm-6">
						<textarea rows="6" class="form-control" id="projectAbstrct" placeholder="Project Abstract" name="projectAbstract"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="description" class="col-sm-2 control-label">Description:</label>
					<div class="col-sm-6">
						<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="startdate" class="col-sm-2 control-label">Project Start Date:</label>
					<div class="col-sm-6">
						<input value="" type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
					</div>
				</div>
				<div class="form-group">
					<label for="enddate" class="col-sm-2 control-label">Project End Date:</label>
					<div class="col-sm-6">
						<input value="" type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspector" class="col-sm-2 control-label">Project Investigator Name:</label>
					<div class="col-sm-6">
						<input autocomplete="off" value="" type="text" list="txtHint" class="form-control" id="projectInspector" placeholder="Project Investigator Name" name="projectInspector" onkeyup="showHint(this.value)">
						<datalist id="txtHint"></datalist>
					</div>
				</div>
				<div class="form-group">
					<label for="projectInspectorStartDate" class="col-sm-2 control-label">Project Investigator Start Date:</label>
					<div class="col-sm-6">
						<input value="" type="text" class="form-control" id="projectInspectorStartDate" placeholder="Project Investigator Start Date" name="facultystartdate">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button id='addButton' type='button' class="btn btn-primary">
							Add Project Co-PI (Optional)
						</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button id='removeButton' type='button' class="btn btn-primary">
							Remove Last CO-PI
						</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button action="editSingleProject.php" type="submit" class="btn btn-primary">
							Create Project
						</button>
					</div>
				</div>
				<br/>
			</form>
			</div>
		</div>
	</div>
EOT;
	require_once("php/footer.php");
?>