<?php
/**
 * Faire order status management.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Faire;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Faire order status management.
 */
class Order_Status {

	/**
	 *  Translates Faire order statuses to WooCommerce order statuses.
	 *
	 * @param string $faire_order_status The Faire order status.
	 *
	 * @return string The WooCommerce order status.
	 */
	public static function get_faire_to_wc_order_status(
		string $faire_order_status
	): string {
		$order_status_mapping = array(
			'new'         => 'faire-new',
			'processing'  => 'processing',
			'pre_transit' => 'processing',
			'in_transit'  => 'completed',
			'delivered'   => 'completed',
			'backordered' => 'faire-backordered',
			'canceled'    => 'cancelled',
		);

		return $order_status_mapping[ strtolower( $faire_order_status ) ];
	}

}
