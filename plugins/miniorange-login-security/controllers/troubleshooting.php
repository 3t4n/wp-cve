<?php
/**
 * This file used to show the plugin troubleshooting steps.
 *
 * @package miniorange-login-security/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $momls_wpns_utility,$mo2f_dir_name;

	require $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'troubleshooting.php';
