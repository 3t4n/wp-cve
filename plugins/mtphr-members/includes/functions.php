<?php
/**
 * General functions
 *
 * @package Metaphor Members
 */




/**
 * Add the thumbnail support
 *
 * @since 1.0.0
 */
add_theme_support( 'post-thumbnails', array('mtphr_member') );




/**
 * Add WooSidebars support
 *
 * @since 1.0.0
 */
add_post_type_support( 'mtphr_member', 'woosidebars' );




/**
 * Return a value from the options table if it exists,
 * or return a default value
 *
 * @since 1.0.9
 */
function mtphr_members_settings() {

	// Get the options
	$settings = get_option( 'mtphr_members_settings', array() );

	$defaults = array(
		'slug' => 'members',
		'singular_label' => __( 'Member', 'mtphr-members' ),
		'plural_label' => __( 'Members', 'mtphr-members' ),
		'public' => 'true',
		'has_archive' => 'false',
	);
	$defaults = apply_filters( 'mtphr_members_default_settings', $defaults );

	return wp_parse_args( $settings, $defaults );
}



add_action( 'plugins_loaded', 'mtphr_members_localization' );
/**
 * Setup localization
 *
 * @since 1.0.5
 */
function mtphr_members_localization() {
  load_plugin_textdomain( 'mtphr-members', false, 'mtphr-members/languages/' );
}



 
/* --------------------------------------------------------- */
/* !Update the social sites to 1.1.0 - 1.1.0 */
/* --------------------------------------------------------- */

function mtphr_members_social_update_1_1_0( $instance ) {

	if( is_array($instance) && count($instance) > 0 && isset($instance[0]['site'])  ) {
	
		$updated = array();
		foreach( $instance as $site ) {
			if( isset($site['site']) ) {
				$updated[$site['site']] = $site['link'];
			}
		}
		$instance = $updated;
	}

	return $instance;
}



