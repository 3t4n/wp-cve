<?php

global $mollaUtility,$mo_lla_dirName;


if(current_user_can( 'manage_options' )  && isset($_POST['option']))
{
	switch(sanitize_text_field($_POST['option']))
	{
		case "mo_lla_content_protection":
			lla_handle_content_protection($_POST);						break;
		case "mo_lla_enable_comment_spam_blocking":
			lla_handle_comment_spam_blocking($_POST);					break;
		case "mo_lla_activate_recaptcha_for_comments":
			lla_handle_comment_recaptcha($_POST);						break;
		case "mo_lla_comment_recaptcha_settings":
			lla_save_comment_recaptcha($_POST);						break;		
	}
}


$protect_wp_config 		= get_option('protect_wp_config') 		   			 ? "checked" : "";
$wp_config 		   		= site_url().DIRECTORY_SEPARATOR.'wp-config.php';
$protect_wp_uploads		= get_option('prevent_directory_browsing') 			 ? "checked" : "";
$wp_uploads 	   		= get_site_url().DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'uploads';
$disable_file_editing	= get_option('disable_file_editing') 	   			 ? "checked" : ""; 
$plugin_editor			= get_site_url().DIRECTORY_SEPARATOR.'wp-admin'.DIRECTORY_SEPARATOR.'plugin-editor.php';
$comment_spam_protect	= get_option('mo_lla_enable_comment_spam_blocking') ? "checked" : "";
$enable_recaptcha 		= get_option('mo_lla_activate_recaptcha_for_comments')     ? "checked" : "";
$test_recaptcha_url		= "";
$InactiveUserLEnable	= get_option('mo_lla_inactive_user_logout')		 ? "checked" : "";

if($enable_recaptcha)
{
	$test_recaptcha_url	= add_query_arg( array('option'=>'testrecaptchaconfig'), sanitize_text_field($_SERVER['REQUEST_URI'] ));	
	$captcha_site_key	= get_option('mo_lla_recaptcha_site_key'  );
	$captcha_secret_key = get_option('mo_lla_recaptcha_secret_key');
}

include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'content-protection.php';

/* CONTENT PROTECTION FUNCTIONS */

//Function to save content protection settings
function lla_handle_content_protection()
{
	isset($_POST['protect_wp_config']) 			? update_option('protect_wp_config'			, sanitize_text_field($_POST['protect_wp_config']))			: update_option('protect_wp_config'			,0);
	isset($_POST['prevent_directory_browsing']) ? update_option('prevent_directory_browsing', sanitize_text_field($_POST['prevent_directory_browsing']))	: update_option('prevent_directory_browsing',0);
	isset($_POST['disable_file_editing']) 		? update_option('disable_file_editing'		, sanitize_text_field($_POST['disable_file_editing']))		: update_option('disable_file_editing'		,0);
	do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONTENT_PROTECTION_ENABLED'),'SUCCESS');
}


//Function to handle comment spam blocking
function lla_handle_comment_spam_blocking($postvalue)
{
	$enable  = isset($postvalue['mo_lla_enable_comment_spam_blocking']) ? true : false;
	update_option('mo_lla_enable_comment_spam_blocking', $enable);
	if($enable)
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONTENT_SPAM_BLOCKING'),'SUCCESS');
	else
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONTENT_SPAM_BLOCKING_DISABLED'),'ERROR');
}


//Function to handle reCAPTCHA for comments
function lla_handle_comment_recaptcha($postvalue)
{
	$enable  = isset($postvalue['mo_lla_activate_recaptcha_for_comments']) ? true : false;
	update_option('mo_lla_activate_recaptcha_for_comments', $enable);
	if($enable)
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONTENT_RECAPTCHA'),'SUCCESS');
	else
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONTENT_RECAPTCHA_DISABLED'),'ERROR');
}

function lla_save_comment_recaptcha($postvalue){

	update_option('mo_lla_recaptcha_site_key', sanitize_text_field($postvalue['mo_lla_recaptcha_site_key']));
	update_option('mo_lla_recaptcha_secret_key', sanitize_text_field($postvalue['mo_lla_recaptcha_secret_key']));
	do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('RECAPTCHA_ENABLED'),'SUCCESS');
}