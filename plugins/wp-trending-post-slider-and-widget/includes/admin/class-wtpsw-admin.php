<?php
/**
 * Admin Class
 *
 * Handles admin side functionality of plugin
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wtpsw_Admin {

	function __construct() {

		// Action to register admin menu
		add_action( 'admin_menu', array( $this, 'wtpsw_register_menu' ));

		// Action to register plugin settings
		add_action ( 'admin_init', array( $this,'wtpsw_admin_processes' ));
	}

	/**
	 * Function to register admin menus
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_register_menu() {

		// Register Setting Page
		add_menu_page ( __( 'Trending Post', 'wtpsw' ), __( 'Trending Post', 'wtpsw' ), 'manage_options', 'wtpsw-settings', array( $this, 'wtpsw_settings_page' ), 'dashicons-star-filled' );

		// Register How It Work Page
		add_submenu_page( 'wtpsw-settings', __( 'Getting Started - WP Trending Post Slider and Widget', 'wtpsw' ), __( 'Getting Started', 'wtpsw' ), 'edit_posts', 'wtpsw-help', array( $this, 'wtpsw_designs_page' ) );

		// Register plugin premium page
		add_submenu_page( 'wtpsw-settings', __( 'Upgrade To Premium - Trending/Popular Post Slider and Widget', 'wtpsw' ), '<span style="color:#ff2700">'.__( 'Upgrade To Premium', 'wtpsw' ).'</span>', 'manage_options', 'wtpsw-premium', array( $this, 'wtpsw_premium_page' ));
	}

	/**
	 * Function to handle the setting page html
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_settings_page() {
		include_once( WTPSW_DIR . '/includes/admin/form/wtpsw-settings.php' );
	}

	/**
	 * How It Work Page
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_designs_page() {
		include_once( WTPSW_DIR . '/includes/admin/wtpsw-how-it-works.php' );
	}

	/**
	 * Upgrade to PRO Vs Free 
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_premium_page() {
		include_once( WTPSW_DIR . '/includes/admin/form/premium.php' );
	}

	/**
	 * Function register setings
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_admin_processes() {

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && $_GET['message'] == 'wtpsw-plugin-notice' ) {
			set_transient( 'wtpsw_install_notice', true, 604800 );
		}

		register_setting( 'wtpsw_plugin_options', 'wtpsw_options', array( $this, 'wtpsw_validate_options' ));
	}

	/**
	 * Validate Settings Options
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_validate_options( $input ){

		$input['post_types']	= isset( $input['post_types'] )	? $input['post_types']	: array();

		return $input;
	}
}

$wtpsw_Admin = new Wtpsw_Admin();