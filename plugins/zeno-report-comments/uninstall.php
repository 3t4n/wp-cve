<?php
/*
 * This file will be called when pressing 'Delete' on Dashboard > Plugins.
 */


// if uninstall.php is not called by WordPress, die.
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
	die();
}

$option_names = array(
		'zrcmnt_threshold',
		'zrcmnt_enabled',
		'zrcmnt_admin_notification',
		'zrcmnt_admin_notification_each',
	);

foreach ( $option_names as $option_name ) {

	delete_option( $option_name );

	// for site options in Multisite
	delete_site_option( $option_name );

}
