<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
$option_keys = array(
	'cm_typesense_plugin_activate',
);

foreach ( $option_keys as $option_key ) {
	delete_option( 'cm_typesense_plugin_activate' );
	delete_option( 'cm_typesense_admin_settings' );
	delete_option( 'typesense_customizer_instant_search' );
}
