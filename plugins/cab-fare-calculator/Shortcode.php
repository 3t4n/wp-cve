<?php

require_once TBLIGHT_PLUGIN_PATH . 'controllers/onepage.php';

class TBLight_Shortcode {

	// class constructor
	public function __construct() {
		 add_action( 'init', 'tblight_output_buffer' );

		load_theme_textdomain( 'cab-fare-calculator', TBLIGHT_PLUGIN_PATH . '/languages' );

		if ( ! empty( $_GET['pg'] ) && $_GET['pg'] == 'thanks' ) {
			add_shortcode( 'taxibooking-form', array( $this, 'show_booking_thanks' ) );
		} else {
			add_shortcode( 'taxibooking-form', array( $this, 'show_booking_form' ) );
		}
	}

	/**
	 * Show the Booking Form
	 *
	 * @return void
	 */
	public function show_booking_form() {
		ob_start();

		require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php';

		$elsettings = BookingHelper::config();
		$offset     = get_option( 'gmt_offset' );
		// $booking_form_url = $_SERVER['REQUEST_URI'];

		global $wp;
		$booking_form_url = home_url( add_query_arg( array(), $wp->request ) );

		$countryObj      = CompanyHelper::getCountryById( $elsettings->default_country );
		$default_country = ! empty( $countryObj ) ? $countryObj->country_2_code : '';

		require_once TBLIGHT_PLUGIN_PATH . 'views/tblight/default.php';

		wp_enqueue_style( 'tblight-bootstrap-style' );
		wp_enqueue_style( 'tblight-bootstrap-datetimepicker-style' );
		wp_enqueue_style( 'tblight-main-style' );
		wp_enqueue_style( 'tblight-media-style' );
		wp_enqueue_style( 'tblight-chosen-style' );
		wp_enqueue_style( 'tblight-fontawesome-style' );
		wp_enqueue_style( 'tblight-font-family', 'https://fonts.googleapis.com/css?family=Muli' );

		wp_enqueue_script( 'jquery' );

		// wp_enqueue_script( 'tblight-jquery' );
		wp_enqueue_script( 'tblight-chosen-script' );
		wp_enqueue_script( 'tblight-moment-script' );
		wp_enqueue_script( 'tblight-bootstrap-popper-script' );
		wp_enqueue_script( 'tblight-bootstrap-script' );
		wp_enqueue_script( 'tblight-datetimepicker-script' );
		wp_enqueue_script( 'tblight-google-maps-script' );
		wp_enqueue_script( 'tblight-core-script' );
		wp_enqueue_script( 'tblight-googlegeo-script' );
		wp_enqueue_script( 'tblight-main-script' );

		$output = ob_get_clean();

		return $output;
	}

	public function show_booking_thanks() {
		 ob_start();

		global $wp;

		require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';
		$booking_form_url = home_url( add_query_arg( array(), $wp->request ) );
		require_once TBLIGHT_PLUGIN_PATH . 'views/tblight/thanks.php';

		$output = ob_get_clean();

		return $output;
	}
}
