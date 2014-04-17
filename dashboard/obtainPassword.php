<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		include_once('inc/utilityFunction.php');
		include_once('dbconnect.php');
		$exist = false;
		if($_POST['user-type'] == 'faculty') {
			$result = mysql_query("SELECT * from `A_FACULTY` WHERE UserName = '".$_POST['email']."';", $conn) or die(mysql_error());
			
			$num_row = mysql_num_rows($result);
			
			if($num_row == 1)
			{
				$exist = true;	
				$result = mysql_fetch_assoc($result);
				sendEmailPassword($_POST['email'], $result['FirstName'], $result['UserName'], $result['UserPassword']);			
			}		
		} else if($_POST['user-type'] == 'admin') {
			$result = mysql_query("SELECT * from `A_ADMIN` WHERE Email = '".$_POST['email']."';", $conn) or die(mysql_error());
			
			$num_row = mysql_num_rows($result);
			
			if($num_row == 1)
			{
				$exist = true;	
				$result = mysql_fetch_assoc($result);
				sendEmailPassword($_POST['email'], $result['FirstName'], $result['Email'], $result['Password']);		
			}	
		} else {
			die ("?????");
		}
		if($exist)
		{	
			echo "An email with your password has been sent";
		} else {
			echo "No email present in DB.";
		}
	} else {
		echo <<<EOT
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <title>Forgot Password</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
    <form action="obtainPassword.php" class="form-signin" method='post' id='login-form'>
		<h2 class='form-signin-heading'>Please Enter Email:</h2>   
        <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <select name="user-type" form="login-form" class="form-control">
	 		<option value="faculty">Faculty/Staff</option>
	 		<option value="admin">Admin</option>
		 </select><br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Send Password</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
EOT;
	}
?>