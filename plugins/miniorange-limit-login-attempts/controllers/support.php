<?php

	global $mo_lla_dirName;
	
	if(current_user_can( 'manage_options' )  && isset($_POST['option']))
	{
		switch(sanitize_text_field($_POST['option']))
		{
			case "mo_lla_send_query":
				lla_handle_support_form(sanitize_email($_POST['query_email']),sanitize_text_field($_POST['query']),sanitize_text_field($_POST['query_phone']));
				break;
		}
	}

	$current_user 	= wp_get_current_user();
	$email 			= get_option("mo_lla_admin_email");
	$phone 			= get_option("mo_lla_admin_phone");

	
	if(empty($email))
		$email 		= $current_user->user_email;

	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'support.php';


	/* SUPPORT FORM RELATED FUNCTIONS */

	//Function to handle support form submit
	function lla_handle_support_form($email,$query,$phone)
	{
		if( empty($email) || empty($query) )
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('SUPPORT_FORM_VALUES'),'SUCCESS');
			return;
		}

	
		$subject = 'Query for Limit Login Attempts - '.$email;

		$contact_us = new Mo_lla_MocURL();
		$submited = json_decode($contact_us->submit_contact_us($email, $phone, $query,$subject),true);

		if(json_last_error() == JSON_ERROR_NONE && $submited) 
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('SUPPORT_FORM_SENT'),'SUCCESS');
			return;
		}
			
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('SUPPORT_FORM_ERROR'),'ERROR');
	}