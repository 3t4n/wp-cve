<?php

namespace EasyBooking;

/**
*
* Admin: Easy Booking menu.
* @version 3.0.3
*
**/

defined( 'ABSPATH' ) || exit;

class Admin_Menu {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 10 );

	}

	/**
	*
	* Add plugin settings page to the dashboard.
	*
	**/
	public function add_menu_page() {

		// Create an "Easy Booking" page in the admin menu.
		$menu_page = add_menu_page(
			'Easy Booking',
			'Easy Booking',
			apply_filters( 'easy_booking_settings_capability', 'manage_options', 'easy-booking' ),
			'easy-booking',
			'',
			'dashicons-calendar-alt',
			58
		);
		
		// Trigger a function when loading settings page.
		add_action( 'load-'. $menu_page, array( $this, 'save_settings' ) );
		
	}

	/**
	*
	* Add an action hook when saving Easy Booking settings.
	*
	**/
	public function save_settings() {

		// If settings are updated
	  	if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {

	  		// array() parameter for backward compatibility
			do_action( 'easy_booking_save_settings', array() );
			
	   	}

	}

}

new Admin_Menu();