<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	add_action( 'wp_head', 'frontendHeader'  );
	add_action( 'wp_footer', 'frontendFooter'  );
	/**
	* Outputs script / CSS to the frontend header
	*/
	function frontendHeader() {
		output( 'optHeader' );
	}

	/**
	* Outputs script / CSS to the frontend footer
	*/
	function frontendFooter() {
		output( 'optFooter' );
	}
	/**
	* Outputs the given setting, if conditions are met
	*
	* @param string $setting Setting Name
	* @return output
	*/
	function output( $setting ) {
		// Ignore admin, feed, robots or trackbacks
		if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		// Get meta
		$meta = w2w_get_option( $setting );
		if ( empty( $meta ) ) {
			return;
		}
		if ( trim( $meta ) === '' ) {
			return;
		}
		// Output
		echo wp_unslash( $meta );
	}