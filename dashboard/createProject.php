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
				<label for="projecttitle" class="col-sm-1 control-label">Abstract:</label>
				<div class="col-sm-4">
					<textarea rows="6" class="form-control" id="projectAbstrct" placeholder="Project Abstract" name="projectAbstract"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-sm-1 control-label">Description:</label>
				<div class="col-sm-4">
					<textarea rows="6" class="form-control" id="description" placeholder="Project Description" name="description"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="startdate" class="col-sm-1 control-label">Project Start Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="startdate" placeholder="Project Start Date" name="startdate">
				</div>
			</div>
			<div class="form-group">
				<label for="enddate" class="col-sm-1 control-label">Project End Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="enddate" placeholder="Project End Date" name="enddate">
				</div>
			</div>
			<div class="form-group">
				<label for="projectInspector" class="col-sm-1 control-label">Project Inspector:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="projectInspector" placeholder="Project Inspector" name="projectInspector">
				</div>
			</div>
			<div class="form-group">
				<label for="projectInspectorStartDate" class="col-sm-1 control-label">project Inspector Start Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="projectInspectorStartDate" placeholder="project Inspector Start Date" name="projectInspectorStartDate">
				</div>
			</div>
			<div class="form-group">
				<div class="control-group">
				<label for="projectCoInspector" class="col-sm-1 control-label">Project Co-Inspector:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="projectCoInspector" placeholder="Project Co-Inspector" name="projectCoInspector">
				</div>
				</div>
			</div>
			<input type='button' value='Add Co-PI' id='addButton' />
    		<input type='button' value='Remove Co-PI' id='removeButton' />
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-4">
					Note:  Once a project has been created, you can assign team members, upload files, and make changes to the above fields again.<br>
					Issue1: Co-Inspector layout.<br>
					Issue2: Co-Inspector have to have a date for his or her co-inspection on this project.<br>
					Note: we may want to redesign the co-inspector stuff; get those out and follow the below scenario.<br>
					Note: In the perspective of this functionality, I am thinking we can add the default PI only, then add other participatants seperately.<br>
					Note: for the same person, he or she can join a project as a normal reseacher or as a PI or as a Co-PI. <br>
					On the other hands, a person can grant one or more as a normal reseacher or as a PI or as a Co-PI.<br>
					
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