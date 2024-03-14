<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


Class WPSBC_Submenu_Page_Backup extends WPSBC_Submenu_Page {

	/**
	 * Helper init method that runs on parent __construct
	 *
	 */
	protected function init() {

		add_action( 'admin_init', array( $this, 'register_admin_notices' ), 10 );

	}


	/**
	 * Callback method to register admin notices that are sent via URL parameters
	 *
	 */
	public function register_admin_notices() {

		if( empty( $_GET['wpsbc_message'] ) )
			return;

		// Calendar insert success
		wpsbc_admin_notices()->register_notice( 'import_file_success', '<p>' . __( 'Data from the file has been imported successfully.', 'wp-simple-booking-calendar' ) . '</p>' );

	}


	/**
	 * Callback for the HTML output for the Calendar page
	 *
	 */
	public function output() {

		include 'views/view-backup.php';

	}

}