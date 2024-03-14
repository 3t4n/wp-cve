<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

register_deactivation_hook( SOCIAL_ROCKET_FILE, 'social_rocket_deactivate' );

function social_rocket_deactivate( $network_wide ) {
	
	global $wpdb;
	
	if ( is_multisite() && $network_wide ) {
	
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			social_rocket_deactivate_tasks();
			restore_current_blog();
		}
		
	} else {
	
		social_rocket_deactivate_tasks();
		
	}
	
}


function social_rocket_deactivate_tasks() {
	
	global $wpdb;
	
	$settings = get_option( 'social_rocket_settings' );
	
	$table_name = $wpdb->prefix . 'social_rocket_count_queue';
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	
	delete_option( '_social_rocket_facebook_invalid_token' );
	delete_option( 'sr_admin_notice_sr_invalid_facebook_token' );
	delete_transient( 'sr_hide_sr_invalid_facebook_token_notice' );
	
	if ( isset( $settings['delete_settings'] ) && $settings['delete_settings'] ) {
	
		$table_name = $wpdb->prefix . 'social_rocket_count_data';
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		
		$wpdb->get_results( "DELETE FROM $wpdb->postmeta WHERE meta_key IN ( 'social_rocket_og_description', 'social_rocket_og_image', 'social_rocket_og_title', 'social_rocket_pinterest_browser_extension', 'social_rocket_pinterest_browser_extension_location', 'social_rocket_pinterest_description', 'social_rocket_pinterest_image', 'social_rocket_twitter_message', 'social_rocket_floating_position', 'social_rocket_inline_position', 'social_rocket_total_shares' )" );
		$wpdb->get_results( "DELETE FROM $wpdb->usermeta WHERE meta_key IN ( 'social_rocket_facebook_profile_url', 'social_rocket_twitter_username' )" );
		
		delete_option( 'social_rocket_settings' );
		delete_option( '_social_rocket_buffer_last_call' );
		delete_option( '_social_rocket_facebook_last_call' );
		delete_option( '_social_rocket_pinterest_last_call' );
		delete_option( '_social_rocket_reddit_last_call' );
		delete_option( '_social_rocket_twitter_last_call' );
		
	}
	
}
