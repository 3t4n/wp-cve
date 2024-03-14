<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @package Popup Anything on Click
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Popupaoc_Script {

	function __construct() {

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'popupaoc_front_style' ), 99 );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'popupaoc_front_script' ) );

		// Action to add style & script in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'popupaoc_admin_script_style' ) );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0
	 */
	function popupaoc_front_style() {

		// Registring font awesome css
		if( ! wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_register_style( 'font-awesome', POPUPAOC_URL.'assets/css/font-awesome.min.css', array(), POPUPAOC_VERSION );
		}

		// Registring and enqueing public css
		wp_register_style( 'popupaoc-public-style', POPUPAOC_URL."assets/css/popupaoc-public.css", array(), POPUPAOC_VERSION );

		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'popupaoc-public-style' );
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0
	 */
	function popupaoc_front_script() {

		global $paoc_preview;

		// Taking some variables
		$add_legacy_js = popupaoc_get_option( 'add_js' );

		if( ! wp_script_is( 'wpos-custombox-legacy-js', 'registered' ) ) {
			wp_register_script( 'wpos-custombox-legacy-js', POPUPAOC_URL.'assets/js/custombox.legacy.min.js', array('jquery'), POPUPAOC_VERSION, false );
		}

		// Enqueue js in header
		if( ! empty( $add_legacy_js ) && $add_legacy_js == 2 ) {
			wp_enqueue_script('wpos-custombox-legacy-js');
		}

		if( ! wp_script_is( 'wpos-custombox-popup-js', 'registered' ) ) {
			wp_register_script( 'wpos-custombox-popup-js', POPUPAOC_URL.'assets/js/custombox.min.js', array('jquery'), POPUPAOC_VERSION, true );
		}

		wp_register_script( 'popupaoc-public-js', POPUPAOC_URL."assets/js/popupaoc-public.js", array('jquery'), POPUPAOC_VERSION, true );
		wp_enqueue_script('popupaoc-public-js');
	}

	/**
	 * Function to add Scripts and Styles at admin side
	 * 
	 * @package Popup Anything on Click
	 * @since 1.0
	 */
	function popupaoc_admin_script_style( $hook ) {

		global $post, $typenow, $wp_version;

		/***** Registering Styles *****/
		// Registring jQuery UI style
		wp_register_style( 'jquery-ui', POPUPAOC_URL.'assets/css/jquery-ui.min.css', array(), POPUPAOC_VERSION );

		// Registring Select 2 Style
		wp_register_style( 'select2', POPUPAOC_URL.'assets/css/select2.min.css', array(), POPUPAOC_VERSION );

		// Registring admin css
		wp_register_style( 'popupaoc-admin-style', POPUPAOC_URL.'assets/css/popupaoc-admin.css', array(), POPUPAOC_VERSION );


		/***** Registering Scripts *****/
		// Registring select 2 script
		wp_register_script( 'select2', POPUPAOC_URL.'assets/js/select2.min.js', array('jquery'), POPUPAOC_VERSION, true );

		// Color Picker Alpha
		wp_register_script( 'wp-color-picker-alpha', POPUPAOC_URL.'assets/js/wp-color-picker-alpha.js', array('wp-color-picker'), POPUPAOC_VERSION, true );

		// Registring admin script
		wp_register_script( 'popupaoc-admin-script', POPUPAOC_URL.'assets/js/popupaoc-admin.js', array('jquery'), POPUPAOC_VERSION, true );
		wp_localize_script( 'popupaoc-admin-script', 'PaocAdmin', array(
														'is_mobile'					=> ( wp_is_mobile() ) ? 1 : 0,
														'code_editor'				=> ( version_compare( $wp_version, '4.9' ) >= 0 )				? 1 : 0,
														'syntax_highlighting'		=> ( 'false' === wp_get_current_user()->syntax_highlighting )	? 0 : 1,
														'cofirm_msg'				=> esc_js( __( 'Are you sure you want to do this?', 'popup-anything-on-click' ) ),
														'sorry_msg'					=> esc_js( __( 'Sorry, Something happened wrong.', 'popup-anything-on-click' ) ),
														'select2_input_too_short'	=> esc_js( __( 'Search popup by its name or ID', 'popup-anything-on-click' ) ),
														'select2_remove_all_items'	=> esc_js( __( 'Remove all items', 'popup-anything-on-click' ) ),
														'select2_remove_item'		=> esc_js( __( 'Remove item', 'popup-anything-on-click' ) ),
														'select2_searching'			=> esc_js( __( 'Searching…', 'popup-anything-on-click' ) ),
													));

		// If screen is Post Add / Edit page
		if( $typenow == POPUPAOC_POST_TYPE && ( $hook == 'post.php' || $hook == 'post-new.php' ) ) {

			/*===== Styles =====*/
			wp_enqueue_style( 'wp-color-picker' );	// ColorPicker
			wp_enqueue_style('jquery-ui');			// jQuery UI

			/*===== Scripts =====*/
			wp_enqueue_script( 'wp-color-picker' );			// ColorPicker
			wp_enqueue_script( 'wp-color-picker-alpha' );	// ColorPicker Alpha
			wp_enqueue_script( 'jquery-ui-datepicker' );	// Datepicker
			wp_enqueue_media();								// For media uploader

			// Check WordPress Version then Initialize Code Editor
			if( version_compare( $wp_version, '4.9' ) >= 0 ) {

				// WP CSS Code Editor
				wp_enqueue_code_editor( array(
					'type'			=> 'text/css',
					'codemirror'	=> array(
										'indentUnit'	=> 2,
										'tabSize'		=> 2,
										'lint'			=> false,
									),
				) );
			}
		}

		// Plugin Setting Page & Campaign Page
		if( $hook == POPUPAOC_POST_TYPE.'_page_popupaoc-settings' ) {

			// Style
			wp_enqueue_style('select2');

			// Script
			wp_enqueue_script('select2');
		}

		// Common Script & Style For All Pages
		if( $typenow == POPUPAOC_POST_TYPE ) {
			wp_enqueue_style('popupaoc-admin-style');	// Admin style
			wp_enqueue_script('popupaoc-admin-script');	// Admin script
		}
	}
}

$popupaoc_script = new Popupaoc_Script();