    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/docs.min.js"></script>
    <script src="../dashboard/js/script.js"></script>
    <script src="../dashboard/js/coProjectInspector.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
  </body>
</html>
<?php
	if(!isset($_SESSION['UID']))
	{
		header("Location: ../login.php?message=denied");
	}
?>
