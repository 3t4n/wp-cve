<?php
/**
 * Delete options upon uninstall to prevent issues with other plugins and leaving trash behind.
 */

// Exit, if uninstall.php was not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

$option_name = 'MatomoAnalyticsPP';
$plugin_name = "simple-matomo-tracking-code/simple-matomo-tracking-code.php";

if( !is_multisite()) {
	delete_option( $option_name );
}
else {
	$sites = get_sites( [ 'number' => 99999 ] );

	foreach ( $sites as $site ) {
		switch_to_blog( intval( $site->blog_id ) );

		deactivate_plugins( $plugin_name );
		delete_option( $option_name );

		restore_current_blog();
	}

	delete_site_option( $option_name );
}
?>