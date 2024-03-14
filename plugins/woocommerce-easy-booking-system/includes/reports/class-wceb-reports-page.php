<?php

namespace EasyBooking;

/**
*
* Admin: Reports page.
* @version 3.2.0
*
**/

defined( 'ABSPATH' ) || exit;

class Reports_Page {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_reports_page' ), 10 );

	}

	/**
	*
	* Add reports page into "Easy Booking" menu
	*
	**/
	public function add_reports_page() {

		// Create a "Reports" page inside "Easy Booking" menu
		$reports_page = add_submenu_page(
			'easy-booking',
			esc_html__( 'Reports', 'woocommerce-easy-booking-system' ),
			esc_html__( 'Reports', 'woocommerce-easy-booking-system' ),
			apply_filters( 'easy_booking_settings_capability', 'manage_options', 'easy-booking-reports' ),
			'easy-booking-reports',
			array( $this, 'display_reports_page' ),
			1
		);

		// Load scripts on this page only
        add_action( 'admin_print_scripts-'. $reports_page, array( $this, 'load_reports_scripts' ) );

	}

	/**
	*
	* Load HTML for reports page.
	*
	**/
	public function display_reports_page() {
		include_once( 'views/html-wceb-reports-page.php' );
	}

	/**
    *
    * Load CSS and JS for reports page.
    *
    **/
    public function load_reports_scripts() {

        // Bookings tab
        wp_register_script(
            'wceb-bookings-reports',
            wceb_get_file_path( 'admin', 'wceb-reports', 'js' ),
            array( 'jquery', 'pickadate', 'pickadate.language', 'select2', 'wc-enhanced-select', WC_ADMIN_APP ),
            '1.0',
            true
        );

        wp_register_style(
            'wceb-bookings-reports-styles',
            wceb_get_file_path( 'admin', 'wceb-reports', 'css', WCEB_PLUGIN_FILE ),
            array( 'picker', 'woocommerce_admin_styles' ),
            1.0
        );

        if ( ! isset( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && $_GET['tab'] === 'bookings' ) ) {

            wp_enqueue_script( 'wceb-bookings-reports' );
            wp_enqueue_style( 'wceb-bookings-reports-styles' );

        }

        // Calendar tab
        wp_register_script(
            'wceb-calendar-reports',
            wceb_get_file_path( 'admin', 'wceb-calendar-reports', 'js', WCEB_PLUGIN_FILE ),
            array( 'jquery', 'pickadate', 'pickadate.language', WC_ADMIN_APP ),
            '1.0',
            true
        );

        // Get booking mode (Days or Nights)
        $booking_mode = get_option( 'wceb_booking_mode' );

        // Get last available date
        $last_date           = get_option( 'wceb_last_available_date' );
        $current_date        = date( 'Y-m-d' );
        $last_available_date = date( 'Y-m-d', strtotime( $current_date . ' +' . $last_date . ' days' ) );

        wp_register_style(
            'wceb-calendar-reports-picker',
            wceb_get_file_path( 'admin', 'wceb-calendar-reports-picker', 'css', WCEB_PLUGIN_FILE ),
            true
        );

        if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'calendar' ) {

            wp_enqueue_script( 'wceb-calendar-reports' );

            wp_add_inline_script( 'wceb-calendar-reports', 'const wceb_calendar_reports = ' . json_encode(
                array(
                    'last_date'     => esc_html( $last_available_date ),
                    'booking_mode'  => esc_html( $booking_mode ),
                    'bookings'      => wceb_get_bookings_sorted_by_date()
                )
            ), 'before' );

            wp_enqueue_style( 'wceb-calendar-reports-picker' );

        }

        // Action hook to load extra scripts on the reports page
        do_action( 'easy_booking_load_report_scripts' );

    }

}

new Reports_Page();