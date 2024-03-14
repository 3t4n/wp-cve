<?php
/**
 * File responsible for defining helper methods that deal with orders.
 *
 * Author:          Uriahs Victor
 * Created on:      26/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Helpers
 */

namespace Lpac_DPS\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Order;

/**
 * Order helper class.
 *
 * Responsible for defining methods that deal with orders
 *
 * @package Lpac_DPS\Helpers
 * @since 1.1.0
 */
class Order {

	/**
	 * Order object.
	 *
	 * @var WC_Order
	 * @since 1.1.0
	 */
	private static $order;

	/**
	 * The order type.
	 *
	 * Whether delivery or pickup.
	 *
	 * @var string
	 * @since 1.1.0
	 */
	private static $order_type;

	/**
	 * Class constructor.
	 *
	 * @param int $order_id
	 * @return void
	 * @since 1.1.0
	 */
	public function __construct( int $order_id ) {
		self::$order      = wc_get_order( $order_id );
		self::$order_type = self::getOrderType();
	}

	/**
	 * Get the order type.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getOrderType(): string {
		return self::$order->get_meta( 'lpac_dps_order_type' );
	}

	/**
	 * Get the delivery/pickup date set by user.
	 *
	 * @return string The delivery date for the order.
	 * @since 1.1.0
	 */
	public static function getOrderFulfillmentDate(): string {
		$order_type = self::$order_type;
		$date       = self::$order->get_meta( "lpac_dps_{$order_type}_date" );
		return Functions::getFormattedDate( $date );
	}

	/**
	 * Get the delivery date set by user.
	 *
	 * @return string The delivery date for the order.
	 * @since 1.1.0
	 */
	public static function getOrderFulfillmentTime(): string {
		$order_type = self::$order_type;
		return self::$order->get_meta( "lpac_dps_{$order_type}_time" );
	}

	/**
	 * Get the order Location name.
	 *
	 * @return string The order location name.
	 * @since 1.1.0
	 */
	public static function getOrderLocationName(): string {
		$order_type = self::$order_type;
		return self::$order->get_meta( "lpac_dps_{$order_type}_location" );
	}

	/**
	 * Get the order Location coordinates.
	 *
	 * @return string The order location coordinates.
	 * @since 1.1.0
	 */
	public static function getOrderLocationCords(): string {
		$order_type = self::$order_type;
		return self::$order->get_meta( "lpac_dps_{$order_type}_location_cords" );
	}
}
