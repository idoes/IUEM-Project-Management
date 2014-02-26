<?php
	include_once('./php/header.php');
?>
 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
      <h1 class="page-header">Manage Projects</h1>
 </div>    
 
 <div class="container-fluid">
      <div class="row">	        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#</th>
						<th>Project Title</th>
						<th>Created On</th>
						<th>Members</th>
						<th>Edit</th>
					</tr>
					<?php
						//TODO
						//Add for loop to go through project DB and print out tr/td info
					?>
					<tr>
						<td>1</td>
						<td>Project 1</td>
						<td>2/14/2014</td>
						<td>
							<ul>
							  <li>User 1 (Creator)</li>
							  <li>User 2</li>
							  <li>User 3</li>
							</ul>
						</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Project 2</td>
						<td>2/04/2014</td>
						<td>
							<ul>
							  <li>User 1 (Creator)</li>
							  <li>User 2</li>
							  <li>User 3</li>
							  <li>User 4</li>
							</ul>
						</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Project 3</td>
						<td>1/01/2013</td>
						<td>
							<ul>
							  <li>User 1 (Creator)</li>
							</ul>
						</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>4</td>
						<td>Project 4</td>
						<td>9/10/2012</td>
						<td>
							<ul>
							  <li>User 1 (Creator)</li>
							  <li>User 2</li>
							</ul>
						</td>
						<td><a href="#">Edit</a></td>
					</tr>
					<tr>
						<td>5</td>
						<td>Project 5</td>
						<td>1/29/2013</td>
						<td>
							<ul>
							  <li>User 1 (Creator)</li>
							  <li>User 2</li>
							  <li>User 3</li>
							  <li>User 4</li>
							  <li>User 5</li>
							</ul>
						</td>
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
