<?php // Contact Form X Ajax

if (!defined('ABSPATH')) exit;

require_once('core-email.php');
require_once('core-form.php');
require_once('core-helpers.php');
require_once('core-validate.php');

//

function contactformx_ajax() {
	
	check_ajax_referer('cfx-frontend', 'nonce');
	
	$name      = isset($_POST['name'])      ? $_POST['name']      : '';
	$website   = isset($_POST['website'])   ? $_POST['website']   : '';
	$email     = isset($_POST['email'])     ? $_POST['email']     : '';
	$subject   = isset($_POST['subject'])   ? $_POST['subject']   : '';
	$message   = isset($_POST['message'])   ? $_POST['message']   : '';
	$challenge = isset($_POST['challenge']) ? $_POST['challenge'] : '';
	$recaptcha = isset($_POST['recaptcha']) ? $_POST['recaptcha'] : '';
	$custom    = isset($_POST['custom'])    ? $_POST['custom']    : '';
	$carbon    = isset($_POST['carbon'])    ? $_POST['carbon']    : false;
	$agree     = isset($_POST['agree'])     ? $_POST['agree']     : false;
	$url       = isset($_POST['url'])       ? $_POST['url']       : false;
	
	$array = array(
		
		'display'   => '',
		'success'   => '',
		'errors'    => '',
		'name'      => stripslashes(strip_tags(trim($name))), 
		'website'   => stripslashes(strip_tags(trim($website))), 
		'email'     => stripslashes(strip_tags(trim($email))), 
		'subject'   => stripslashes(strip_tags(trim($subject))), 
		'message'   => stripslashes(trim($message)), //
		'challenge' => stripslashes(strip_tags(trim($challenge))),
		'recaptcha' => stripslashes(strip_tags(trim($recaptcha))),
		'custom'    => stripslashes(strip_tags(trim($custom))),
		'carbon'    => stripslashes(strip_tags(trim($carbon))),
		'agree'     => stripslashes(strip_tags(trim($agree))),
		'url'       => stripslashes(strip_tags(trim($url))),
		
	);
	
	$array = contactformx_send_email($array);
	
	echo json_encode($array);
	
	die(); //
	
}