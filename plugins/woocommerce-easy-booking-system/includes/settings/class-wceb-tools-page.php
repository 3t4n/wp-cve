<?php

namespace EasyBooking;

/**
*
* Admin: Tools page.
* @version 3.0.3
*
**/

defined( 'ABSPATH' ) || exit;

class Tools_Page {
	
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_tools_page' ), 10 );
		
	}

	/**
	*
	* Add "Tools" page into "Easy Booking" menu.
	*
	**/
	public function add_tools_page() {

		$tools_page = add_submenu_page(
			'easy-booking',
			__( 'Tools', 'woocommerce-easy-booking-system' ),
			__( 'Tools', 'woocommerce-easy-booking-system' ),
			apply_filters( 'easy_booking_settings_capability', 'manage_options', 'easy-booking-tools' ),
			'easy-booking-tools',
			array( $this, 'display_tools_page' ),
			3
		);

	}

	/**
	*
	* Load HTML for "Tools" page.
	*
	**/
	public function display_tools_page() {
		include_once( 'views/html-wceb-tools-page.php' );
	}

}

new Tools_Page();