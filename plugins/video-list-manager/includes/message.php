<?php 
/**
 * Generic function to show a message to the user using WP's 
 * standard CSS classes to make use of the already-defined
 * message colour scheme.
 *
 * @param $message The message you want to tell the user.
 * @param $errormsg If true, the message is an error, so use 
 * the red message style. If false, the message is a status 
  * message, so use the yellow information message style.
 */
function showMessage($message, $errormsg = false)
{
	$msg = "";
	if ($errormsg == 0) {
		$msg .= '<div id="message" class="error">';
	}
	else {
		$msg .= '<div id="message" class="updated fade">';
	}

	$msg .= "<p><strong>$message</strong></p></div>";
	echo $msg;
}

add_action("showMessage", 10, 2);    
?>