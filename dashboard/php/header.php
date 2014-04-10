<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body onload="init();">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="dashboard.php">IUEM Project Management System</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="help.php">Help</a></li>
            <li><a href="php/logout.php">Logout</a></li>
          	<li><img src="../assets/ico/iu_ico.png" style="padding-right: 20px; padding-left: 15px;"></img></li>
          </ul>
        </div>
      </div>
    </div>

<!-- Remove this after PHP implementation is complete -->
<!-- Which part to be removed? Assume the following list items, then why? -->
<div class="col-sm-3 col-md-2 sidebar">
  <ul class="nav nav-sidebar">
    <!-- The functionality of Creating Database user is performed by Super Administrator.
    If this is the case, then we are not dealing with profile scale of information. 
    Simply reduce the scale which createDBUser.php is performing. -->
    <?php
    	if(isset($_SESSION['ACCESS_LEVEL']))
		{
			if($_SESSION['ACCESS_LEVEL'] == 'SUPER_ADMIN')
			{
				echo <<<EOT
				    <li><a href="createFaculty.php">Create Faculty</a></li>
				    <li><a href="manageFaculty.php">Manage Faculty</a></li>
				    <li><a href="createAdminUser.php">Create Admin</a></li>
				    <li><a href="manageAdmins.php">Manage Admins</a></li>
EOT;
			}
		} else {
			echo isset($_SESSION['ACCESS_LEVEL']) ? 'true' : 'false';
			die();
			header("Location: ../login.php?message=denied");			
		}
	?>
    <!--li><a href="#">Create Faculty</a></li>
    <li><a href="#">Manage Faculty</a></li-->
    <li><a href="createProject.php">Create Project</a></li>
    <li><a href="manageProjects.php">Manage Projects</a></li>
</div>
<div id='ajax-page-content'>
<!-- // -->