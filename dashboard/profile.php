<?php
	include_once('./php/header.php');
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
  <h1 class="page-header">My Profile</h1>
</div>

<div class="col-sm-9 col-sm-offset-3 col-md-5 col-md-offset-2 main">
	<table class="table">
  		<tr>
  			<th>My Profile</th>
  			<th></th>
  		</tr>
  		<tr>
  			<td><b>First Name:</b></td>
  			<td>Name</td>
  		</tr>
  		<tr>
  			<td><b>Middle Name:</b></td>
  			<td>Name</td>
  		</tr>
  		<tr>
  			<td><b>Last Name:</b></td>
  			<td>Name</td>
  		</tr>
  		<tr>
  			<td><b>Email:</b></td>
  			<td>email@email.com</td>
  		</tr>
  		<tr>
  			<td><b>Title:</b></td>
  			<td>Title</td>
  		</tr>
  		<tr>
  			<td><b>Position:</b></td>
  			<td>Position</td>
  		</tr>
  		<tr>
  			<td><b>Office Location:</b></td>
  			<td>Room</td>
  		</tr>
  		<tr>
  			<td><b>Bio:</b></td>
  			<td>Bio</td>
  		</tr>
  		<tr>
  			<td><b>Bio-Photo</b></td>
  			<td>Headshot</td>
  		</tr>
	</table>
	<!-- The following need to be delete after PHP implementation. -->
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-10">
			Note1: Current DB Design wouldn't let a faculty edit his or her profile in FACULTY table. 
					Since current DB Design, assume only instance of ADMIN can Create, Read, Update, delete FACULTY table.<br>	
		</div>
	</div>
	<div class="col-sm-offset-0 col-sm-10">
	<br/>
		<button type="submit" class="btn btn-primary btn-lg">
			Edit My Profile
		</button>
	</div>	
</div>

<?php
	include_once('./php/footer.php');
?>