<?php
/**
 * This is main controller file.
 *
 * @package broken-link-finder/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $moblc_utility, $moblc_dir_path;
$controller = $moblc_dir_path . 'controllers' . DIRECTORY_SEPARATOR;


if ( current_user_can( 'administrator' ) ) {
	if ( isset( $_GET['page'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is not required here.
		$_page = sanitize_text_field( wp_unslash( $_GET['page'] ) );//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is not required here.
			include_once $controller . 'navbar.php';
			echo '<table class="moblc_main_table"><tr><td class="moblc_layout">';
		switch ( $_page ) {
			case 'moblc_manual':
				include_once $controller . 'manual.php';
				break;
			case 'moblc_settings':
				include_once $controller . 'settings.php';
				break;
			case 'moblc_report':
				include_once $controller . 'report.php';
				break;
			case 'moblc_troubleshooting':
				include_once $controller . 'troubleshoot.php';
				break;
		}
			echo '</table>';
	}
}
