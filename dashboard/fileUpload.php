<?php
include_once('./php/header.php');
include_once('dbconnect.php');

	$message_out = "";
	$interactive_message = "";
	//$theProjectID = $_GET['projectID'];
	$theProjectID = 1;
	
	//check file directory issue
	if (!file_exists("upload/project_$theProjectID/")) 
	{
    	mkdir("upload/project_$theProjectID/", 0777, true);
	}
	define("UPLOAD_DIR", "upload/project_$theProjectID/");
	
	
	
	//populate title
	$theRequest = mysql_query("SELECT Title FROM A_PROJECT WHERE ProjectID = '".$theProjectID."';", $conn) 
						or die(mysql_error());
	$theResult = mysql_fetch_assoc($theRequest);
	$theProjectTitle = (string)$theResult["Title"];
	
	//server setting checking 
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	
	//TODO	
	//print_r($_FILES);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if (($_FILES["userfile"]["error"]) === 4)
		{
			$interactive_message .= "<br>Please click uplod file button in order to upload. <br>
					Then click the File Upload Button. <br>";
		}else
		{
			$myFileArray = $_FILES["userfile"];
			
			if ($myFileArray["error"] !== UPLOAD_ERR_OK)
			{
				$interactive_message .= "<br>An error has occur, please re-do the process.";
				exit;
			}
			
			// ensure a safe filename
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFileArray["name"]);
			
			// don't overwrite an existing file
		    /*
		    $i = 0;
		    $parts = pathinfo($name);
		    while (file_exists(UPLOAD_DIR . $name)) 
		    {
		        $i++;
		        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
		    }
			*/
			
			//Check file exist or not
			if(file_exists(UPLOAD_DIR . $name)) 
		    {
		        //TODO waiting user input || message box pop out
		    }
			
			
			
			//TODO
			//$interactive_message .= $myFileArray["tmp_name"];
		    
		    // preserve file from temporary directory
		    echo $success = move_uploaded_file($myFileArray["tmp_name"],
		        UPLOAD_DIR . $name);
		    if (!$success) 
		    { 
		        $interactive_message .= "<br>Unable to save file.";
		        exit;
		    }else
			{
				$interactive_message .= "<br>File has been successully uploaded to the directory: " . UPLOAD_DIR;
				
				//DB stuff
				$request = mysql_query("INSERT INTO  A_ATTACHMENT (AttachmentID, ProjectID, ItemServerLink) 
										VALUES (NULL, ".$theProjectID.", '".UPLOAD_DIR.$name."');", $conn) 
								or $interactive_message .= "<br>Error Occur";
				 
			}
				
													
		}
	}
	
	
	
	
?>
<?php
	

	echo <<<EOT
	   <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	      <h1 class="page-header">$interactive_message</h1>
	    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<form action="fileUpload.php" class="form-horzontal" role="form" method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label for="projecttitle" class="col-sm-1 control-label">Upload File to the project named as: {$theProjectTitle}</label>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<br/>
						Upload This File:<br />
						<input name="userfile" type="file" /><br />
					</div>
				</div>
				
				
				
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<br/>
						<button action="fileUpload.php" type="submit" class="btn btn-primary">
							File Upload
						</button>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
EOT;
?>
