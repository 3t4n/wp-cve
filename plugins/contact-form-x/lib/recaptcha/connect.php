<?php // Google reCAPTCHA for PHP >= 5.3.0

// PHP Global space backslash class requires >= 5.3.0

// Google reCAPTCHA v2, library version 1.1.3 @ https://github.com/google/recaptcha

// Supports allow_url_fopen/file_get_contents and cURL

if (!defined('ABSPATH')) die();

require_once('recaptcha.php');

if (ini_get('allow_url_fopen')) {
	
	// file_get_contents: allow_url_fopen = on
	$recaptcha = new \ReCaptcha\ReCaptcha($private);
	
} elseif (extension_loaded('curl')) {
	
	// cURL: allow_url_fopen = off
	$recaptcha = new \ReCaptcha\ReCaptcha($private, new \ReCaptcha\RequestMethod\CurlPost());
	
} else {
	
	$recaptcha = null;
	
	error_log('Contact Form X: Google reCAPTCHA: allow_url_fopen and curl both disabled');
	
}

if (isset($recaptcha)) {
	
	$response = isset($_POST['recaptcha']) ? $recaptcha->verify($_POST['recaptcha'], contactformx_get_ip()) : null;
	
} else {
	
	$response = null;
	
	// error_log('Contact Form X: Google reCAPTCHA: $recaptcha variable not set');
	
}

if ($response->isSuccess()) {
	
	return true;
	
} else {
	
	$errors = $response->getErrorCodes();
	
	if (!empty($errors) && is_array($errors)) {
		
		foreach ($errors as  $error) {
			
			// error_log('Contact Form X: Google reCAPTCHA: '. $error);
			
		}
		
	} else {
		
		// error_log('Contact Form X: Google reCAPTCHA: '. $errors);
		
	}
	
}

return false;