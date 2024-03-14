<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.deviodigital.com/
 * @since      1.0
 *
 * @package    DTWC
 * @subpackage DTWC/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    DTWC
 * @subpackage DTWC/public
 * @author     Devio Digital <contact@deviodigital.com>
 */
class Delivery_Times_For_WooCommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {

		if ( is_checkout() ) {
			// jQuery UI stylesheet.
			wp_enqueue_style( $this->plugin_name . '-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dtwc-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {

		if ( is_checkout() ) {
			$days = dtwc_business_delivery_days();

			$day_num = array();

			if ( $days ) {

				foreach ( $days as $day ) {
					if ( 'sunday' == $day ) {
						$day_num['sunday'] = 0;
					}
					if ( 'monday' == $day ) {
						$day_num['monday'] = 1;
					}
					if ( 'tuesday' == $day ) {
						$day_num['tuesday'] = 2;
					}
					if ( 'wednesday' == $day ) {
						$day_num['wednesday'] = 3;
					}
					if ( 'thursday' == $day ) {
						$day_num['thursday'] = 4;
					}
					if ( 'friday' == $day ) {
						$day_num['friday'] = 5;
					}
					if ( 'saturday' == $day ) {
						$day_num['saturday'] = 6;
					}
				}

			}

			// Delivery prep time.
			$delivery_prep = dtwc_delivery_prep_time();

			// Set the delivery prep time for the strtotime.
			if ( '1' == $delivery_prep ) {
				$strtotime = '+' . $delivery_prep . 'hour';
			} elseif ( $delivery_prep > 1 ) {
				$strtotime = '+' . $delivery_prep . 'hours';
			} else {
				$strtotime = 'now';
			}

			// Get the prep time based on the settings in delivery prep.
			$prep_time = date( 'H:i', strtotime( $strtotime, strtotime( current_time( 'H:i' ) ) ) );

			// Set variables.
			$open_time  = strtotime( dtwc_business_opening_time() );
			$close_time = strtotime( dtwc_business_closing_time() );

			// Create delivery time.
			$delivery_time = $open_time;

			// Round to next 30 minutes (30 * 60 seconds)
			$delivery_time = ceil( $delivery_time / ( 30 * 60 ) ) * ( 30 * 60 );

			// Times array.
			$times = array();

			// Loop through and add delivery times based on open/close times.
			while( $delivery_time <= $close_time && $delivery_time >= $open_time ) {
				// Add delivery time to array of times.
				$times[] = date( 'H:i', $delivery_time );

				// Update delivery time variable.
				$delivery_time = strtotime( '+30 minutes', $delivery_time );
			}

			// Encode and then decode the times for JavaScript usage.
			$times = json_encode( $times );
			$times = json_decode( $times );

			// Load the datepicker script.
			wp_enqueue_script( 'jquery-ui-datepicker' );
			// Load the main scripts.
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dtwc-public.js', array( 'jquery' ), $this->version, false );

			// Create options for js file.
			$translation_array = array(
				'minDate'       => dtwc_delivery_prep_days(),
				'maxDays'       => dtwc_delivery_preorder_days(),
				'deliveryDays'  => $day_num,
				'deliveryTimes' => $times,
				'prepTime'      => $prep_time,
				'firstDay'      => get_option( 'start_of_week' )
			);
			wp_localize_script( $this->plugin_name, 'dtwcSettings', $translation_array );
		}

	}

}
