<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

register_activation_hook( SOCIAL_ROCKET_FILE, 'social_rocket_activate' );

function social_rocket_activate( $network_wide ) {
	global $wpdb;
	$default_settings = array(
		'active_networks'            => array(),
		'facebook'                   => array(
			'access_token'               => '',
		),
		'floating_buttons'           => array(
			'background_color'                            => '#ffffff',
			'border'                                      => 'none',
			'border_radius'                               => 0,
			'border_size'                                 => 0,
			'button_alignment'                            => 'center',
			'button_color_scheme'                         => 'default',
			'button_color_scheme_custom_background'       => 'network_background',
			'button_color_scheme_custom_background_color' => '#787878',
			'button_color_scheme_custom_border'           => 'network_border',
			'button_color_scheme_custom_border_color'     => '#666666',
			'button_color_scheme_custom_icon'             => 'network_icon',
			'button_color_scheme_custom_icon_color'       => '#ffffff',
			'button_color_scheme_custom_hover'            => 'network_hover',
			'button_color_scheme_custom_hover_color'      => '#ffffff',
			'button_color_scheme_custom_hover_bg'         => 'network_hover_bg',
			'button_color_scheme_custom_hover_bg_color'   => '#666666',
			'button_color_scheme_custom_hover_border'     => 'network_hover_border',
			'button_color_scheme_custom_hover_border_color' => '#666666',
			'button_show_cta'                             => false,
			'button_size'                                 => 100,
			'button_style'                                => 'square',
			'default_position'                            => 'left',
			'margin_bottom'                               => 0,
			'margin_right'                                => 0,
			'networks'                                    => array(),
			'padding'                                     => '5px',
			'rounding'                                    => true,
			'show_counts'                                 => true,
			'show_counts_min'                             => 1,
			'show_total'                                  => false,
			'show_total_min'                              => 1,
			'total_color'                                 => '#252525',
			'total_position'                              => 'after',
			'total_show_icon'                             => true,
			'vertical_offset'                             => '',
			'vertical_position'                           => 'center',
		),
		'floating_mobile_breakpoint' => 782,
		'floating_mobile_setting'    => 'default',
		'inline_buttons'             => array(
			'border'                                      => 'solid',
			'border_radius'                               => 0,
			'border_size'                                 => 1,
			'button_alignment'                            => 'left',
			'button_color_scheme'                         => 'default',
			'button_color_scheme_custom_background'       => 'network_background',
			'button_color_scheme_custom_background_color' => '#787878',
			'button_color_scheme_custom_border'           => 'network_border',
			'button_color_scheme_custom_border_color'     => '#666666',
			'button_color_scheme_custom_icon'             => 'network_icon',
			'button_color_scheme_custom_icon_color'       => '#ffffff',
			'button_color_scheme_custom_hover'            => 'network_hover',
			'button_color_scheme_custom_hover_color'      => '#ffffff',
			'button_color_scheme_custom_hover_bg'         => 'network_hover_bg',
			'button_color_scheme_custom_hover_bg_color'   => '#666666',
			'button_color_scheme_custom_hover_border'     => 'network_hover_border',
			'button_color_scheme_custom_hover_border_color' => '#666666',
			'button_show_cta'                             => true,
			'button_size'                                 => 100,
			'button_style'                                => 'rectangle',
			'default_archive_position'                    => 'none',
			'default_position'                            => 'above',
			'heading_alignment'                           => 'default',
			'heading_element'                             => 'h4',
			'heading_text'                                => __( 'Share', 'social-rocket' ),
			'margin_bottom'                               => 5,
			'margin_right'                                => 5,
			'networks'                                    => array(),
			'rounding'                                    => true,
			'saved_settings'                              => array(),
			'show_counts'                                 => true,
			'show_counts_min'                             => 1,
			'show_total'                                  => false,
			'show_total_min'                              => 1,
			'total_color'                                 => '#252525',
			'total_position'                              => 'after',
			'total_show_icon'                             => true,
		),
		'inline_mobile_breakpoint'   => 782,
		'inline_mobile_setting'      => 'default',
		'pinterest'                  => array(
			'image_fallback'             => 'featured',
		),
		'social_identity'            => array(
			'pinterest'                  => '',
			'twitter'                    => '',
		),
		'tweet_settings'             => array(
			'saved_settings'             => array(
				'default'                    => array(
					'accent_color'               => '#3c87b2',
					'background_color'           => '#429cd6',
					'border'                     => 'none',
					'border_color'               => '#dddddd',
					'border_radius'              => 0,
					'border_size'                => 1,
					'include_url'                => true,
					'include_via'                => true,
					'cta_text'                   => __( 'Click to Tweet', 'social-rocket' ),
					'cta_position'               => 'right',
					'cta_color'                  => '#ffffff',
					'name'                       => __( 'Default', 'social-rocket' ),
					'text_color'                 => '#ffffff',
					'text_size'                  => 24,
				),
			),
		),
		'decimal_places'             => 1,
		'decimal_separator'          => '.',
		'disable_fontawesome'        => false,
		'disable_og_tags'            => false,
		'disable_twitter_cards'      => false,
		'auto_backup'                => true,
		'auto_fix_gutenberg'         => true,
		'master_throttle'            => 1,
		'refresh_interval'           => 3600,
		'delete_settings'            => false,
		'db_version'                 => SOCIAL_ROCKET_DBVERSION,
	);
	if ( is_multisite() && $network_wide ) {
		// Get all blogs in the network and activate plugin on each one
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			social_rocket_activate_db_delta();
			if ( ! get_option( 'social_rocket_settings' ) ) {
				update_option( 'social_rocket_settings', $default_settings );
			}
			restore_current_blog();
		}
	} else {
		social_rocket_activate_db_delta();
		if ( ! get_option( 'social_rocket_settings' ) ) {
			update_option( 'social_rocket_settings', $default_settings );
		}
	}
	do_action( 'social_rocket_activated' );
}

function social_rocket_activate_db_delta() {
	
	global $wpdb;
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$charset_collate = $wpdb->get_charset_collate();
	
	// counts table
	$table_name = $wpdb->prefix . 'social_rocket_count_data';
	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NULL,
		term_id bigint(20) unsigned NULL,
		user_id bigint(20) unsigned NULL,
		url varchar(2048) NULL,
		data text NOT NULL,
		last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		KEY post_id (post_id),
		KEY term_id (term_id),
		KEY user_id (user_id),
		KEY url (url)
	) $charset_collate;";
	dbDelta( $sql );
	
	// queue table
	$table_name = $wpdb->prefix . 'social_rocket_count_queue';
	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		hash varchar(32) NOT NULL,
		data text NOT NULL,
		request_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id),
		UNIQUE KEY hash (hash)
	) $charset_collate;";
	dbDelta( $sql );
}
