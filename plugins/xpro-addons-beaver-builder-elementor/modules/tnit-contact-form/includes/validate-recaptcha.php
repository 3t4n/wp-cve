<?php

// Do recaptcha validation here so we can only load for php 5.3 and above.
require_once FL_BUILDER_DIR . 'includes/vendor/recaptcha/autoload.php';

if ( function_exists( 'curl_exec' ) ) {
	$recaptcha = new \ReCaptcha\ReCaptcha( $this->user_data['recaptcha']['secret_key'], new \ReCaptcha\RequestMethod\CurlPost() );
} else {
	$recaptcha = new \ReCaptcha\ReCaptcha( $this->user_data['recaptcha']['secret_key'] );
}

$resp = $recaptcha->verify( $recaptcha_response, $_SERVER['REMOTE_ADDR'] );

if ( $resp->isSuccess() ) {
	$response['error'] = false;
} else {
	$response['error']   = true;
	$response['message'] = '<strong>reCAPTCHA Error: </strong>';
	$error_codes         = array();
	foreach ( $resp->getErrorCodes() as $code ) {
		$error_codes[] = $code;
	}
	$response['message'] .= implode( ' | ', $error_codes );
}
