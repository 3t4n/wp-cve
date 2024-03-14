<?php
/**
 * Uninstall Simple social button.
 *
 * @package SimpleSocialButtons
 * @author WPBrigade
 * @since 2.1.5
 * @version 5.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	 exit;
}

$ssb_advance_setting = get_option( 'ssb_advanced' );

if ( '1' != $ssb_advance_setting['ssb_uninstall_data'] ) {
	return;
}


// Array of Plugin's Option.
$ssb_unintstall_option = array(
	'ssb_networks',
	'ssb_themes',
	'ssb_positions',
	'ssb_inline',
	'ssb_sidebar',
	'ssb_flyin',
	'ssb_popup',
	'ssb_media',
	'ssb_advanced',
	'ssb_ngg_gallery',
	'ssb_click_to_tweet',
	'ssb_active_time',
	'ssb_follow_twitter_token',
	'ssb_review_dismiss',
	'widget_ssb_widget',
	'ssb_pr_version', // $this->pluginPrefix . 'version'.
);


if ( ! is_multisite() ) {
	// Delete all plugin Options.
	foreach ( $ssb_unintstall_option as $option ) {
		if ( get_option( $option ) ) {
			delete_option( $option );
		}
	}
} else {

	global $wpdb;
	$ssb_blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $ssb_blog_ids as $blog_id ) {

		switch_to_blog( $blog_id );


		// Delete all plugin Options.
		foreach ( $ssb_unintstall_option as $option ) {
			if ( get_option( $option ) ) {
				delete_option( $option );
			}
		}

		restore_current_blog();
	}
}
