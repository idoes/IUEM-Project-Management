<?php
	include_once('./php/header.php');
	
	//test
	print_r($_POST);
	if(isset($_POST['formSubmit']))
	{
		echo "<br>bootstrap button works in terms of input form post method";
	}
?>   
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Create User</h1>
    </div>
    
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<form action="createUser.php" class="form-horizontal" role="form" method="post" id="create-user-post">
			<div class="form-group">
				<label for="firstname" class="col-sm-1 control-label">First Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="firstname" placeholder="First Name (Required)" name="firstname">
				</div>
			</div>
			<div class="form-group">
				<label for="middlename" class="col-sm-1 control-label">Middle Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="middlename" placeholder="Middle Name" name="middlename">
				</div>
			</div>
			<div class="form-group">
				<label for="lastname" class="col-sm-1 control-label">Last Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="lastname" placeholder="Last Name (Required)" name="lastname">
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-1 control-label">Email</label>
				<div class="col-sm-4">
					<input type="email" class="form-control" id="email" placeholder="Email (Required)" name="email">
				</div>
			</div>
			<div class="form-group">
				<label for="title" class="col-sm-1 control-label">Title:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="title" placeholder="Title" name="title">
				</div>
			</div>
			<div class="form-group">
				<label for="position" class="col-sm-1 control-label">Position:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="position" placeholder="Position" name="position">
				</div>
			</div>
			<div class="form-group">
				<label for="officelocation" class="col-sm-1 control-label">Office:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="officelocation" placeholder="Office Location" name="officelocation">
				</div>
			</div>
			<div class="form-group">
				<label for="biotext" class="col-sm-1 control-label">Bio:</label>
				<div class="col-sm-4">
					<textarea rows="6" class="form-control" id="officelocation" placeholder="Bio" name="biotext"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="biophoto" class="col-sm-1 control-label">Bio-Photo</label>
				<div class="col-sm-4">
					<input type="file" class="form-control" id="officelocation" placeholder="" name="biophoto">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					Note:  Faculty can edit all of these fields after account creation.
				</div>
			</div>
	
			<div class="form-group">
			<div class="col-sm-offset-1 col-sm-10">
				<br/>
				<!-- use input instead button -->
				<!-- TODO make your option, technichally either one works.  -->
				<input type="submit" class='btn btn-primary' name="formSubmit" value="Create Faculty User" />
				
				<!--  This one is not working in terms of Form Input Type = submit || help on fix 
				<button type="submit" value="submit" class="btn btn-primary" id="submit" onclick="validateUserPanel();">
					Create User
				</button>
				-->
			</div>
			</div>
	</form>
    </div>


<?php
	include_once('./php/footer.php');
?>