<?php
// If uninstall not called form WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete the option from the options table
if ( is_multisite() ) {
	delete_site_option( 'analyticstracker_settings' );
} else {
	delete_option( 'analyticstracker_settings' );
}
?>