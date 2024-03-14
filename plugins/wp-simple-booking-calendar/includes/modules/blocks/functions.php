<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Function that register the needed categories for the different block
 * available in the plugin
 *
 */
function wpsbc_register_block_categories( $categories, $post ) {

	$categories[] = array(
		'slug'  => 'wp-simple-booking-calendar',
		'title' => 'WP Simple Booking Calendar',
		'icon'	=> ''
	);

	return $categories;

}
add_filter( 'block_categories_all', 'wpsbc_register_block_categories', 10, 2 );


/**
 * Adds the needed JavaScript variables up in the WordPress admin head
 *
 */
function wpsbc_add_javascript_variables() {

	if( ! function_exists( 'get_current_screen' ) )
		return;

	$screen = get_current_screen();

	if( is_null( $screen ) )
		return;

	/**
	 * Filter the post types where the calendar media button should appear
	 *
	 * @param array
	 *
	 */
	$post_types = apply_filters( 'wpsbc_register_block_categories_post_types', array( 'post', 'page' ) );

	if( ! in_array( $screen->post_type, $post_types ) )
	    return;

	$settings = get_option( 'wpsbc_settings', array() );

	echo '<script type="text/javascript">';

	// Add calendars to be globally available
	$calendars = wpsbc_get_calendars( array( 'number' => -1 ) );

	echo 'var wpsbc_calendars = [';

	foreach( $calendars as $key => $calendar ) {
		echo '{ "id" : ' . $calendar->get('id') . ', "name" : "' . $calendar->get('name') . '" }';

		if( $key != count( $calendars ) - 1 )
			echo ',';

	}

	echo '];';

	// Add languages to be globally available
	$languages = wpsbc_get_languages();

	echo 'var wpsbc_languages = [';
	
	if( ! empty( $settings['active_languages'] ) ) {

		foreach( $settings['active_languages'] as $key => $code ) {

			if( empty( $languages[$code] ) )
				continue;

			echo '{ "code" : "' . $code . '", "name" : "' . $languages[$code] . '" }';

			if( $key != count( $settings['active_languages'] ) - 1 )
				echo ',';

		}

	}

	echo '];';

	echo '</script>';


	// Enqueue front-end scripts on the admin part
	wp_register_script( 'wpsbc-front-end-script', WPSBC_PLUGIN_DIR_URL . 'assets/js/script-front-end.min.js', array( 'jquery' ), WPSBC_VERSION, true );
	wp_localize_script('wpsbc-front-end-script', 'wpsbc', array(
		'ajax_url' => admin_url('admin-ajax.php'),
	));
	wp_enqueue_script( 'wpsbc-front-end-script' );

}
add_action( 'admin_enqueue_scripts', 'wpsbc_add_javascript_variables', 10 );