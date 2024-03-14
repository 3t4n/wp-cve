<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Footer Mega Grid Columns
 * @since 1.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Fmgc_Script {

	function __construct() {

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'fmgc_front_style') );

		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'fmgc_admin_style') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Footer Mega Grid Columns
	 * @since 1.2
	 */
	function fmgc_front_style() {

		// Registring and enqueing public css
		wp_register_style( 'fmgc-public-style', FMGC_URL.'assets/css/fmgc-css.css', array(), FMGC_VERSION );
		wp_enqueue_style( 'fmgc-public-style' );
	}

	/**
	 * Enqueue admin script
	 * 
	 * @package Footer Mega Grid Columns
	 * @since 1.2
	 */
	function fmgc_admin_style( $hook ) {

		if( $hook == 'widgets.php' ) {
			// Registring and enqueing admin css
			wp_register_style( 'fmgc-admin-style', FMGC_URL.'assets/css/fmgc-admin.css', array(), FMGC_VERSION );
			wp_enqueue_style( 'fmgc-admin-style' );
		}
	}
}

$fmgc_script = new Fmgc_Script();