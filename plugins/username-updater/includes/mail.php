<?php
/**
 * User update Page
 *
 * @package     mail page
 * @since       1.0.4
 */
// Exit if accessed directly
	if( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	    $to = $user_info->user_email;
        $blogname = get_option('blogname');
        $siteurl  = get_option('siteurl');
		$subject  = 'username changed';
		$headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $blogname <donotreply@site.com>" .  "\r\n";
		$message  = "
		<html>
		  <head>
		    <title>Username Updated</title>
		  </head>
		  <body>
		    <p>Hi, User</p>
		    <p>Your username has been updated for the site $siteurl. Your new username is <b>$name</b></p>
		    <p>Thank You,<br/>$blogname Team</p>
		  </body>
		</html>
		";
		if ( wp_mail( $to, $subject, $message, $headers )) {
			 echo '<div class="updated"><p><strong>Mail Sent to the user</strong></p></div>'; 
		}
		else {
			 echo '<div class="error"><p><strong>Error: Mail could not be sent</strong></p></div>'; 
		}