<?php
/**
 * Class to handle admin related order logic.
 *
 * Author:          Uriahs Victor
 * Created on:      28/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Utilities\OrderUtil;
use Lpac_DPS\Helpers\Functions;

/**
 * Class Order_Edit_Page.
 *
 * @package Lpac_DPS\Controllers\Admin
 */
class Order_View_Page {

	/**
	 * Create our JS variables.
	 *
	 * @return null|string
	 * @since 1.0.0
	 */
	public function get_js_globals(): ?string {

		$order_id = '';
		if ( Functions::usingHPOS() === false ) {
			$order_id = get_the_ID();
		} else {
			$order_id = $_GET['id'] ?? '';
		}

		if ( empty( $order_id ) ) {
			return null;
		}

		if ( 'shop_order' !== OrderUtil::get_order_type( $order_id ) ) {
			return null;
		}

		$order      = wc_get_order( $order_id );
		$order_type = $order->get_meta( 'lpac_dps_order_type' );

		if ( empty( $order_type ) ) {
			return null;
		}

		$date = $order->get_meta( "lpac_dps_{$order_type}_date" );
		$time = $order->get_meta( "lpac_dps_{$order_type}_time" );

		$time_slot_parts = explode( '-', $time );

		// Get the deadline time for the order. Timeslots with a range will use "to" end time e.g 1:00 PM - 2:00 PM, 2:00 PM will be used.
		if ( count( $time_slot_parts ) > 1 ) {
			$end_time = trim( $time_slot_parts[1] );
		} else {
			$end_time = $time_slot_parts[0];
		}

		$js_date = wp_json_encode( $date );

		// Always convert time to 24hr hour format as JS requires this format.
		$js_time = ( ! empty( $end_time ) ) ? wp_json_encode( date( 'H:i', strtotime( $end_time ) ) ) : 'null';

		$expired_text = __( 'Time Expired', 'delivery-and-pickup-scheduling-for-woocommerce' );
		$expired_text = wp_json_encode( $expired_text );

		$data = "
			var lpacExpiredText = $expired_text;
			var lpacOrderDate = $js_date;
			var lpacOrderTime = $js_time;
		 ";

		return $data;
	}
}
