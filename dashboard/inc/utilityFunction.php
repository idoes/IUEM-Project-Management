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




?>