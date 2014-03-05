<?php
	include_once('./php/header.php');
?>
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Current Admins</h1>
 </div>
    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>ROLE</th>
						<th>Edit</th>
					</tr>
					<?php
						//TODO
						//Add for loop to go through user DB and print out tr/td info
					?>
					<tr>
						<td>1</td>
						<td>John</td>
						<td>Doe</td>
						<td>email@email.com</td>
						<td class="success">ADMIN</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Woodrow</td>
						<td>Wilhelm</td>
						<td>WoodrowSWilhelm@armyspy.com</td>
						<td class="success">ADMIN</a></td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Roy</td>
						<td>Walker</td>
						<td>RoyKWalker@teleworm.us</td>
						<td class="success">ADMIN</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>4</td>
						<td>Benjamin</td>
						<td>Grace</td>
						<td>BenjaminDGrace@dayrep.com</td>
						<td class="success">ADMIN</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>5</td>
						<td>Louis</td>
						<td>Hales</td>
						<td>LouisAHales@armyspy.com</td>
						<td class="success">ADMIN</a></td>
						<td><a href="#">Edit</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
	include_once('./php/footer.php');
?>
