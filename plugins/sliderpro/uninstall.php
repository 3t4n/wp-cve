<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	global $wpdb;			
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	
	if ( $blog_ids !== false ) {
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			bqw_sliderpro_delete_all_data();
		}

		restore_current_blog();
	}
} else {
	bqw_sliderpro_delete_all_data();
}

function bqw_sliderpro_delete_all_data() {
	global $wpdb;
	$prefix = $wpdb->prefix;

	$sliders_table = $prefix . 'slider_pro_sliders';
	$slides_table = $prefix . 'slider_pro_slides';
	$layers_table = $prefix . 'slider_pro_layers';

	$wpdb->query( "DROP TABLE $sliders_table, $slides_table, $layers_table" );

	delete_option( 'sliderpro_custom_css' );
	delete_option( 'sliderpro_custom_js' );
	delete_option( 'sliderpro_is_custom_css' );
	delete_option( 'sliderpro_is_custom_js' );
	delete_option( 'sliderpro_load_stylesheets' );
	delete_option( 'sliderpro_load_custom_css_js' );
	delete_option( 'sliderpro_load_unminified_scripts' );
	delete_option( 'sliderpro_hide_inline_info' );
	delete_option( 'sliderpro_hide_getting_started_info' );
	delete_option( 'sliderpro_hide_custom_css_js_warning' );
	delete_option( 'sliderpro_hide_image_size_warning' );
	delete_option( 'sliderpro_cache_expiry_interval' );
	delete_option( 'sliderpro_access' );
	delete_option( 'sliderpro_version' );
	delete_option( 'sliderpro_lightbox_sliders' );
	delete_option( 'sliderpro_add_ons' );

	delete_transient( 'sliderpro_post_names' );
	delete_transient( 'sliderpro_posts_data' );
	delete_transient( 'sliderpro_update_notification_message' );
	delete_transient( 'sliderpro_add_ons_cached_data' );
	
	$wpdb->query( "DELETE FROM " . $prefix . "options WHERE option_name LIKE '%sliderpro_cache_%'" );
}