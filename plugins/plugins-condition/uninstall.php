<?php
/**
 * Uninstall
 *
 * @package Plugins Condition
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
$search_transients = '%plg_cond_datas_%';
$del_transients = $wpdb->get_results(
	$wpdb->prepare(
		"
		SELECT	option_name
		FROM	{$wpdb->prefix}options
		WHERE	option_name LIKE %s
		",
		$search_transients
	)
);

$del_cash_count = 0;
foreach ( $del_transients as $del_transient ) {
	$transient = str_replace( '_transient_', '', $del_transient->option_name );
	$value_del_cash = get_transient( $transient );
	if ( false <> $value_del_cash ) {
		delete_transient( $transient );
	}
}

$option_names = array();
$wp_options = $wpdb->get_results(
	"
	SELECT option_name
	FROM {$wpdb->prefix}options
	WHERE option_name LIKE '%%plg_cond_text_%%'
	"
);
foreach ( $wp_options as $wp_option ) {
	$option_names[] = $wp_option->option_name;
}

/* For Single site */
if ( ! is_multisite() ) {
	foreach ( $option_names as $option_name ) {
		delete_option( $option_name );
		delete_option( 'plg_cond_update_date_time' );
		delete_option( 'plg_cond_notify_interval' );
	}
} else {
	/* For Multisite */
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		foreach ( $option_names as $option_name ) {
			delete_option( $option_name );
			delete_option( 'plg_cond_update_date_time' );
			delete_option( 'plg_cond_notify_interval' );
		}
	}
	switch_to_blog( $original_blog_id );

	/* For site options. */
	foreach ( $option_names as $option_name ) {
		delete_site_option( $option_name );
		delete_site_option( 'plg_cond_update_date_time' );
		delete_site_option( 'plg_cond_notify_interval' );
	}
}
