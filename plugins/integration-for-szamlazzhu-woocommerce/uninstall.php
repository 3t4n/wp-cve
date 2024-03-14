<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

//Check if we need to delete anything
$wc_szamlazz_settings = get_option( 'woocommerce_wc_szamlazz_settings', null );
if($wc_szamlazz_settings['uninstall'] && $wc_szamlazz_settings['uninstall'] == 'yes') {
	// Delete admin notices
	delete_metadata( 'user', 0, 'wc_szamlazz_admin_notices', '', true );

	//Delete options
	$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wc\_szamlazz\_%';");
	$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_wc\_szamlazz\_%';");
	delete_option('woocommerce_wc_szamlazz_settings');
}
