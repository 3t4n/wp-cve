<?php
/*
Plugin Name: Contact Form 7 Leads Tracking
Plugin URI: http://www.wpshore.com/plugins/contact-form-7-leads-tracking/
Description: Adds tracking info to all contact form 7 outgoing emails by using the [tracking-info] shortcode. The tracking info includes the Form Page URL, Original Referrer, Landing Page, User IP, Browser. 
Author: Nablasol
Author URI: http://www.nablasol.com/
Version: 1.0

*/

/*  Copyright 2013 Nablasol
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


// Add the info to the email
function wpshore_wpcf7_before_send_mail($array) {

	global $wpdb;

	if(wpautop($array['body']) == $array['body']) // The email is of HTML type
		$lineBreak = "<br/>";
	else
		$lineBreak = "\n";
		
	$trackingInfo .= $lineBreak . $lineBreak . '-- Tracking Info --' . $lineBreak;
	
	$trackingInfo .= 'The user filled the form on: ' . $_SERVER['HTTP_REFERER'] . $lineBreak;

	if (isset ($_SESSION['OriginalRef']) )
		$trackingInfo .= 'The user came to your website from: ' . $_SESSION['OriginalRef'] . $lineBreak;
		
	if (isset ($_SESSION['LandingPage']) )
		$trackingInfo .= 'The user\'s landing page on your website: ' . $_SESSION['LandingPage'] . $lineBreak;

	if ( isset ($_SERVER["REMOTE_ADDR"]) )
	$trackingInfo .= 'User\'s IP: ' . $_SERVER["REMOTE_ADDR"] . $lineBreak;
	
	if ( isset ($_SERVER["HTTP_X_FORWARDED_FOR"]))
		$trackingInfo .= 'User\'s Proxy Server IP: ' . $_SERVER["HTTP_X_FORWARDED_FOR"] . $lineBreak . $lineBreak;

	if ( isset ($_SERVER["HTTP_USER_AGENT"]) )
		$trackingInfo .= 'User\'s browser is: ' . $_SERVER["HTTP_USER_AGENT"] . $lineBreak;

	$array['body'] = str_replace('[tracking-info]', $trackingInfo, $array['body']);

    return $array;

}
add_filter('wpcf7_mail_components', 'wpshore_wpcf7_before_send_mail');


// Original Referrer 
function wpshore_set_session_values() 
{
	if (!session_id()) 
	{
		session_start();
	}

	if (!isset($_SESSION['OriginalRef'])) 
	{
		$_SESSION['OriginalRef'] = $_SERVER['HTTP_REFERER']; 
	}

	if (!isset($_SESSION['LandingPage'])) 
	{
		$_SESSION['LandingPage'] = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; 
	}

}
add_action('init', 'wpshore_set_session_values');
