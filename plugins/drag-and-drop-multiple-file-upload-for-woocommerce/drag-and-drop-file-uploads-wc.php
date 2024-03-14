<?php

	/**
	* Plugin Name: Drag and Drop Multiple File Upload for WooCommerce
	* Plugin URI: https://profiles.wordpress.org/glenwpcoder
	* Description: This plugin enable user to upload using "Drag & Drop" or "Browse Multiple" file uploads in your WooCommerce Product details page.
	* Text Domain: dnd-file-upload-wc
	* Domain Path: /languages
	* Version: 1.1.2
	* Author: Glen Don L. Mongaya
	* Author URI: http://codedropz.com
	* WC requires at least: 3.5.0
	* WC tested up to: 8.2.1
	* License: GPL2
	**/

	/**  This protect the plugin file from direct access */
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	/** Set plugin constant to true **/
	define( 'DNDMFU_WC', true );

	/**  Define plugin Version */
	define( 'DNDMFU_WC_VERSION', '1.1.2' );

	/**  Define constant Plugin Directories  */
	define( 'DNDMFU_WC_DIR', wp_normalize_path( untrailingslashit( dirname( __FILE__ ) ) ) );

	/**  Define constant Plugin Path  */
	if( ! defined('DNDMFU_WC_PATH') ) {
		define( 'DNDMFU_WC_PATH', 'wc_drag-n-drop_uploads' );
	}

	// require plugin core file
	require_once( DNDMFU_WC_DIR .'/inc/class-dnd-upload-wc.php' );

	// Plugin activate & deactivate hooks
	register_activation_hook( __FILE__, 'dndmfu_wc_activate' );

	/* When plugin activated*/
	function dndmfu_wc_activate() {
		if ( ! wp_next_scheduled ( 'wp_dnd_wc_daily_cron') ) {
			wp_schedule_event( time(), 'hourly', 'wp_dnd_wc_daily_cron' );
		}
	}

	// Deactivation
	register_deactivation_hook( __FILE__, 'dndmfu_wc_deactivate' );

	/* When plugin deactivated */
	function dndmfu_wc_deactivate() {
		wp_clear_scheduled_hook( 'wp_dnd_wc_daily_cron' );
	}

    // HPOS compatibility
    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    });