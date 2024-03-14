<?php


/**
 * class WooCommerceBooking
 *
 * @link       https://appcheap.io
 * @since      3.2.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Plugin;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class WooCommerceBooking extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
		$this->rest_base = 'bookings';
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/find-booked-day-blocks', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'wc_bookings_find_booked_day_blocks' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => '__return_true',
				],
			]
		);
	}

	/**
	 * Get bookings blocks
	 *
	 * @param $request
	 *
	 * @return WP_Error
	 * @since 2.5.0
	 *
	 */
	public function wc_bookings_find_booked_day_blocks( $request ) {
		$product_id = absint( $_GET['product_id'] );

		if ( empty( $product_id ) ) {
			return new WP_Error(
				'get_booking',
				__( 'Missing product ID', "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		try {

			$args                          = array();
			$product                       = get_wc_product_booking( $product_id );
			$args['availability_rules']    = array();
			$args['availability_rules'][0] = $product->get_availability_rules();
			$args['min_date']              = isset( $_GET['min_date'] ) ? strtotime( $_GET['min_date'] ) : $product->get_min_date();
			$args['max_date']              = isset( $_GET['max_date'] ) ? strtotime( $_GET['max_date'] ) : $product->get_max_date();

			$min_date        = ( ! isset( $_GET['min_date'] ) ) ? strtotime( "+{$args['min_date']['value']} {$args['min_date']['unit']}", current_time( 'timestamp' ) ) : $args['min_date'];
			$max_date        = ( ! isset( $_GET['max_date'] ) ) ? strtotime( "+{$args['max_date']['value']} {$args['max_date']['unit']}", current_time( 'timestamp' ) ) : $args['max_date'];
			$timezone_offset = isset( $_GET['timezone_offset'] ) ? $_GET['timezone_offset'] : 0;

			if ( $product->has_resources() ) {
				foreach ( $product->get_resources() as $resource ) {
					$args['availability_rules'][ $resource->ID ] = $product->get_availability_rules( $resource->ID );
				}
			}

			$booked = \WC_Bookings_Controller::find_booked_day_blocks( $product_id, $min_date, $max_date, 'Y-n-j', $timezone_offset );

			$args['partially_booked_days'] = $booked['partially_booked_days'];
			$args['fully_booked_days']     = $booked['fully_booked_days'];
			$args['unavailable_days']      = $booked['unavailable_days'];
			$args['restricted_days']       = $product->has_restricted_days() ? $product->get_restricted_days() : false;

			$buffer_days = array();
			if ( ! in_array( $product->get_duration_unit(), array( 'minute', 'hour' ) ) ) {
				$buffer_days = \WC_Bookings_Controller::get_buffer_day_blocks_for_booked_days( $product, $args['fully_booked_days'] );
			}

			$args['buffer_days'] = $buffer_days;

			return $args;

		} catch ( \Exception $e ) {
			return new WP_Error(
				'get_booking',
				__( $e->getMessage(), "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}
	}
}
