<?php
/**
 * Custom functions for the plugin
 */

/**
 * Plugin Data
 */
function do_etfw_plugin_data() {
	return get_plugin_data( DO_ETFW_DIR . 'easy-twitter-feed-widget.php' );
}

/**
 * Plugin Options Defaults
 *
 * Sane Defaults Logic
 * Plugin will not save default settings to the database without explicit user action
 * and Plugin will function properly out-of-the-box without user configuration.
 *
 * @param string $option - Name of the option to retrieve.
 * @return mixed
 */
function do_etfw_option_default( $option = 'enable' ) {

	$do_etfw_options_default = array (
		'twitter_script' => true,
	);

	if( isset( $do_etfw_options_default[$option] ) ) {
		return $do_etfw_options_default[$option];
	}

	return '';

}

/**
 * Retrieve the plugin option.
 *
 * @param string $option - Name of the option to retrieve.
 * @return mixed
 */
function do_etfw_option( $option = 'twitter_script' ) {

	$do_etfw_options = apply_filters( 'do_etfw_options', get_option( 'do_etfw_options' ) );

	if ( isset( $do_etfw_options[$option] ) ) {
		return $do_etfw_options[$option];
	} else {
		return do_etfw_option_default( $option );
	}

}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function do_etfw_body_classes( $classes ) {

	// WSNB Class
	if ( do_etfw_option( 'twitter_script' ) ) {
		$classes[] = 'do-etfw';
	}

	return $classes;
}
add_filter( 'body_class', 'do_etfw_body_classes' );
