<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 *
 * Handles generic Admin functionailties
 *
 * @package Easy Post Views Count
 * @since 1.0.0
 */
class Epvc_Admin {

	public function __construct()	{		

		add_action( 'admin_menu', array( $this, 'epvc_ltable_add_menu_page' ) );
		add_action( 'admin_init', array($this, 'epvc_register_plugin_settings') );
	}

	/**
	 * Creat menu page
	 *
	 * Adding required menu pages and submenu pages
	 * to manage the plugin functionality
	 * 
	 * @package Easy Post Views Count
	 * @since 1.0.0
	 */
	public function epvc_ltable_add_menu_page() {
		 
		$epvc_setting = add_menu_page( __( 'Easy Post Views Count', 'epvc' ), __( 'Easy Post Views Count', 'epvc' ), 'manage_options','epvc-settings', array($this, 'epvc_display_settings') , EPVC_PLUGIN_URL . '/images/icon.jpg' );
	}

	/**
	 * Includes Settings List
	 * 
	 * Including File for Settings List
	 *
	 * @package Easy Post Views Count
	 * @since 1.0.0
	 */
	public function epvc_display_settings() {
		
		include_once( EPVC_ADMIN_DIR . '/forms/epvc-settings.php' );
		
	}

	/**
	 * Register settings
	 *
	 * Register plugin settings
	 * 
	 * @package Easy Post Views Count
	 * @since 1.0.0
	 */
	public function epvc_register_plugin_settings() {
        register_setting( 'epvc-plugin-settings', 'epvc_settings', array($this, 'epvc_validate_settings_input') );
    }

    /**
	 * Validation and update
	 *
	 * Validate settings input adn update options
	 * 
	 * @package Easy Post Views Count
	 * @since 1.0.0
	 */
    public function epvc_validate_settings_input( $input ) {

    	add_settings_error( 'epvc-notices', '', __( 'Settings have been saved successfully!.', 'epvc' ), 'updated' );
		return $input;
	}
}
return new Epvc_Admin();