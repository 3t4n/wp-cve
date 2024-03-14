<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$options = [
	'seo_backlink_monitor_settings',
	'seo_backlink_monitor_links',
	'seo_backlink_monitor_version',
	'seo_backlink_monitor_session',
];

foreach( $options as $option_name ) {
	delete_option( $option_name );

	// for site options in Multisite
	delete_site_option( $option_name );
}
