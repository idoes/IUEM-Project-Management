<?php 


/*This function prevents malicious users enter multiple email addresses into the email box
 *It makes sure that only one email is entered into the email box.
 *
 *	return funtion: TRUE => valid || FALSE: => not valid
 */
function emailAddressCheck($field)
{
	//filter_var() sanitizes the e-mail
	//address using FILTER_SANITIZE_EMAIL. It removes all illegal email characters
	$field = filter_var($field, FILTER_SANITIZE_EMAIL);
	
	//filter_var() validates the e-mail
	//address using FILTER_VALIDATE_EMAIL
	if(filter_var($field, FILTER_VALIDATE_EMAIL))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}


/*
 * 	This function will validate if user has created a English Letter based input.
 * 	English Letter Definition: [A-Za-z]
 */
function characterCheck($theInput)
{
	$theInput = trim($theInput);
	$letter = false;
	$chars = str_split($theInput);
	
	for($i = 0; $i<strlen($theInput); $i++){
		if (preg_match("/[A-Za-z]/",$chars[$i])){
			$letter = true;
			break;
		}
	
	}//end for
	
	if ($letter == true) 
	{
		return true;
	}
	else 
	{
		return false;
	}
}



/*This function will validate if user created a strong password
 * Longer than 12 characters and alphanumeric letters.
*/
function pwdValidate($field){
	$field = trim($field);
	if (strlen($field) < 12){
		return false;
	}
	else {
		//go through each character and find if there is a number or letter
		$letter = false;
		$number = false;
		$chars = str_split($field);
		for($i = 0; $i<strlen($field); $i++){
			if (preg_match("/[A-Za-z]/",$chars[$i])){
				$letter = true;
				break;
			}

		}

		for($i = 0; $i<strlen($field); $i++){
			if (preg_match("/[0-9]/",$chars[$i])){
				$number = true;
				break;
			}

		}
		if (($letter == true) and ($number == true)){
			return true;
		}
		else return false;




	}




}


//This function will sanitize text input from the web form before inserting into the database
function sqlReplace($text){

	$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out anything between the javascript tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$text = preg_replace($search, '', $text);

	//the function below converts special characters to HTML entities, e.g. < becomes &lt;
	//read here about this function - http://php.net/manual/en/function.htmlspecialchars.php
	$text = htmlspecialchars($text, ENT_QUOTES);

	return $text;
}


?>