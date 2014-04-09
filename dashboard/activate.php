<?php 
	include_once('./php/header.php');
	
		echo <<<EOT
		<div class="container-fluid">
	      <div class="row">	        
	        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	          <h1 class="page-header">Dashboard</h1>
	          <h2 class="sub-header">You have successfully activate your account!  Key = {$_GET['theQueryString']}</h2>
	        </div>
	      </div>
	    </div>
EOT;
	
	include_once('./php/footer.php');
?>