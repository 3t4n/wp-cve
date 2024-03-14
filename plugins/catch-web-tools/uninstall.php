<?php
/**
 * @package Catch Plugins
 * @subpackage Catch Web Tools
 * @author CatchThemes
 * @since Catch Web Tools 0.1
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

$options	=	array(
	'catchwebtools_webmaster',
	'catchwebtools_opengraph',
	'catchwebtools_custom_css',
	'catchwebtools_seo',
	'catchwebtools_social',
	'catchwebtools_catchids',
	'catchwebtools_to_top_options'
);

$transient_options	=	array(
	'catchwebtools_social_display',
	'catchwebtools_custom_css'
);

if ( !is_multisite() ) {
	// For Single site
    foreach ( $options as $option) {
		delete_option( $option );
	}
	foreach ( $transient_options as $option) {
		delete_transient( $option );
	}
} else {
	// For Multisite
    global $wpdb;

	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();

    foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		foreach ( $options as $option) {
			delete_site_option( $option );
		}
		foreach ( $transient_options as $option) {
			delete_transient( $option );
		}
    }
    switch_to_blog( $original_blog_id );
}
