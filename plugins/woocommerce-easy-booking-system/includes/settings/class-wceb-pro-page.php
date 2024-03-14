<?php

namespace EasyBooking;

/**
*
* Admin: Pro page.
* @version 3.0.3
*
**/

defined( 'ABSPATH' ) || exit;

class Pro_Page {
	
	public function __construct() {

		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array( $this, 'add_pro_page' ), 10 );
		} else {
			add_action( 'admin_menu', array( $this, 'add_pro_page' ), 10 );
		}
		
	}

	/**
	*
	* Add "Pro" page into "Easy Booking" menu.
	*
	**/
	public function add_pro_page() {

		$pro_page = add_submenu_page(
			'easy-booking',
			__( 'PRO version', 'woocommerce-easy-booking-system' ),
			'<span class="wceb-menu-pro">' . __( 'PRO version', 'woocommerce-easy-booking-system' ) . '</span>',
			apply_filters( 'easy_booking_settings_capability', 'manage_options', 'easy-booking-pro' ),
			'easy-booking-pro',
			array( $this, 'display_pro_page' ),
			4
		);

	}

	/**
	*
	* Load HTML for "Pro" page.
	*
	**/
	public function display_pro_page() {
		include_once( 'views/html-wceb-pro-page.php' );
	}

}

new Pro_Page();