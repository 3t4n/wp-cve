<?php
/**
 * Uninstall
 *
 * @package Options View
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
/* For Single site */
if ( ! is_multisite() ) {
	delete_option( 'options-view' );
	delete_option( 'optionsview_search_text' );
	delete_option( 'opv_per_page' );
	delete_option( 'opv_filter_user' );
	delete_option( 'opv_current_logs' );
} else {
	/* For Multisite */
	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		delete_option( 'options-view' );
		delete_option( 'optionsview_search_text' );
		delete_option( 'opv_per_page' );
		delete_option( 'opv_filter_user' );
		delete_option( 'opv_current_logs' );
	}
	switch_to_blog( $original_blog_id );

}
