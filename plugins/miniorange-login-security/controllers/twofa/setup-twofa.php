<?php
/**
 * This file includes the UI for 2fa methods options.
 *
 * @package miniorange-login-security/controllers/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$email_registered = 1;
	global $momlsdb_queries;
	$email = get_user_meta( get_current_user_id(), 'email', true );
if ( isset( $email ) ) {
	$email_registered = 1;
} else {
	$email_registered = 0;
}

	require $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'setup-twofa.php';


