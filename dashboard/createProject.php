<?php
	include_once('./php/header.php');
?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create Project</h1>
    </div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<form action="createProject.php" class="form-horizontal" role="form" method="post">
			<div class="form-group">
				<label for="projecttitle" class="col-sm-1 control-label">Title:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="projecttitle" placeholder="Project Title" name="projecttitle">
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-sm-1 control-label">Description:</label>
				<div class="col-sm-4">
					<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="startdate" class="col-sm-1 control-label">Start Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
				</div>
			</div>
			<div class="form-group">
				<label for="enddate" class="col-sm-1 control-label">End Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-4">
					Note:  Once a project has been created, you can assign team members, upload files, and make changes to the above fields again.
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<br/>
					<button type="submit" class="btn btn-primary">
						Create Project
					</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>

<?php
	include_once('./php/footer.php');
?>