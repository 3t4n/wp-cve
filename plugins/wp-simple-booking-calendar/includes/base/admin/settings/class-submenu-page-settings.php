<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPSBC_Submenu_Page_Settings extends WPSBC_Submenu_Page {

	/**
	 * Helper init method that runs on parent __construct
	 *
	 */
	protected function init() {

		add_action( 'admin_init', array( $this, 'register_settings' ), 10 );
		add_action( 'admin_init', array( $this, 'register_admin_notices' ), 10 );

	}

	/**
	 * Callback method to register admin notices that are sent via URL parameters
	 *
	 */
	public function register_admin_notices() {

		// Settings save success
		if( ! empty( $_GET['settings-updated'] ) ) {
			wpsbc_admin_notices()->register_notice( 'settings_save_success', '<p>' . __( 'Settings saved successfully.', 'wp-simple-booking-calendar' ) . '</p>' );
			wpsbc_admin_notices()->display_notice( 'settings_save_success' );
		}

	}


	/**
	 * Returns an array with the page tabs that should be displayed on the page
	 *
	 * @return array
	 *
	 */
	protected function get_tabs() {

		$tabs = array(
			'general' => __( 'General', 'wp-simple-booking-calendar' ),
			'languages' => __( 'Languages', 'wp-simple-booking-calendar' ),
		);

		/**
		 * Filter the tabs before returning
		 *
		 * @param array $tabs
		 *
		 */
		return apply_filters( 'wpsbc_submenu_page_settings_tabs', $tabs );

	}


	/**
	 * Registers the settings option
	 *
	 */
	public function register_settings() {

		register_setting( 'wpsbc_settings', 'wpsbc_settings', array( $this, 'settings_sanitize' ) );

	}


	/**
	 * Sanitizes the settings before saving them to the db
	 *
	 */
	public function settings_sanitize( $settings ) {

		return $settings;

	}


	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		include 'views/view-settings.php';

	}

}