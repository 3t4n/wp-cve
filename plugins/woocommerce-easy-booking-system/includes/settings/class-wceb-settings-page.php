<?php

namespace EasyBooking;

/**
*
* Admin: Settings page.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

class Settings_Page {
	
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 10 );

	}

	/**
	*
	* Add settings page into "Easy Booking" menu.
	*
	**/
	public function add_settings_page() {

		// Create a "Settings" page inside "Easy Booking" menu
		$settings_page = add_submenu_page(
			'easy-booking',
			__( 'Settings', 'woocommerce-easy-booking-system' ),
			__( 'Settings', 'woocommerce-easy-booking-system' ),
			apply_filters( 'easy_booking_settings_capability', 'manage_options', 'easy-booking' ),
			'easy-booking',
			array( $this, 'display_settings_page' )
		);

		// Maybe load scripts on the "Settings" page.
		add_action( 'admin_print_scripts-'. $settings_page, array( $this, 'load_settings_scripts' ) );

		// Add a "Help" tab at the top of the settings page.
		add_action( 'load-' . $settings_page, array( $this, 'add_help_tab' ) );

	}

	/**
	*
	* Load HTML for settings page.
	*
	**/
	public function display_settings_page() {
		include_once( 'views/html-wceb-settings-page.php' );
	}

	/**
	*
	* Load CSS and JS for settings page.
	*
	**/
	public function load_settings_scripts() {

		// WP colorpicker CSS.
		wp_enqueue_style( 'wp-color-picker' );

		// WP colorpicker JS.
	  	wp_enqueue_script(
	  		'color-picker',
	  		plugins_url( 'assets/js/admin/colorpicker.min.js', WCEB_PLUGIN_FILE ),
	  		array( 'wp-color-picker' ),
	  		false,
	  		true
	  	);

	}

	/**
	*
	* Add a "Help" tab at the top of the settings page.
	*
	**/
	public function add_help_tab() {

		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'       => 'wceb-help-support',
			'title'    => __( 'Help and support', 'woocommerce-easy-booking-system' ),
			'content'  => sprintf( __( '%sPlugin settings%sLearn how to set up the plugin to get exactly what you need in the %sdocumentation%s.%sHelp and support%sYou have an issue or a question? Check the %sFAQ%s or send an email.%s', 'woocommerce-easy-booking-system' ), '<h2>', '</h2><p>', '<a href="https://easy-booking.pro/documentation/" target="_blank">', '</a>', '</p><h2>', '</h2><p>', '<a href="https://easy-booking.pro/faq/" target="_blank">', '</a>', '</p>' )
		));

		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'woocommerce-easy-booking-system' ) . '</strong></p>' .
			'<p><a href="https://easy-booking.pro/documentation/" target="_blank">' . __( 'Documentation', 'woocommerce-easy-booking-system' ) . '</a></p>' .
			'<p><a href="https://easy-booking.pro/faq/" target="_blank">' . __( 'FAQ', 'woocommerce-easy-booking-system' ) . '</a></p>'
		);

	}

}

new Settings_Page();