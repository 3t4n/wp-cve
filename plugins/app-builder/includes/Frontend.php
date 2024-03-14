<?php

/**
 * Admin
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 *
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

class Frontend {
	public function __construct() {
		/**
		 * Add style for checkout page
		 * @since 1.0.0
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * Load class for body
		 */
		if ( isset( $_GET[ APP_BUILDER_CHECKOUT_BODY_CLASS ] ) ) {
			add_filter( 'body_class', function ( $classes ) {
				return array_merge( $classes, array( $_GET[ APP_BUILDER_CHECKOUT_BODY_CLASS ] ) );
			} );
		}
	}
}
