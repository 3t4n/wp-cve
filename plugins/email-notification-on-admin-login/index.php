<?php
/* 
    Plugin Name: Email notification on admin login
    Plugin URI: https://www.stefanywebdesign.info 
    Description: Sends an email to a pointed email address when an admin user logs in
    Author: Stefany Newman
    Version: 1.1
    Author URI: https://www.stefanywebdesign.info 

  */ 

# We use emnoti as an abbreviation for our plugin name

# Important configutation data such as start of the seassion
# the website name and the admin email
	require 'config.php';
# Check if the user is an admin
	function _emnoti_check_if_admin(){
		if(current_user_can('manage_options')){
			return true;
		}else{
			return false;
		}
	}
# Gets the time the admin logged in
	function _emnoti_get_time_of_login(){
		$time_of_login = date('l jS F Y');
		return $time_of_login;
	}
# Gets the IP of the user that logged himself as admin
	function _emnoti_get_ip(){
		$sources = array(
	'REMOTE_ADDR',
	'HTTP_X_FORWARDED_FOR',
	'HTTP_CLIENT_IP',
);
	foreach ($sources as $source) {
		if(!empty($_SERVER[$source])){
			$ip = $_SERVER[$source];
		}
	}
	return $ip;
	}
#   Email all the info above to a pointed email address
	function emnoti_send_email(){
		global $website_name;
		if(_emnoti_check_if_admin() === true and !isset($_SESSION['logged_in_once'])){
		$get_time_of_login = _emnoti_get_time_of_login();
		$get_ip =  _emnoti_get_ip();
# Email subject and message
$subject = sprintf('An administrator of your website %s has just logged in!', $website_name);
$message = <<<MESSAGE
		An admin logged in your WordPress website {$website_name} on {$get_time_of_login}
		with the IP: {$get_ip}
MESSAGE;
# Sending of the email notification
				wp_mail(
						ADMIN_EMAIL
						, $subject
						, $message
						);
# We assign 1 as to make so that the script does not sends emails on each page refresh like a deaf rooster
			$_SESSION['logged_in_once'] = 1;
		}
	}
add_action('admin_notices', 'emnoti_send_email');
# EOF 
?>