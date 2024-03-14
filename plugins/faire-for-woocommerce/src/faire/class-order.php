<?php
/**
 * Faire Orders.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Faire;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Faire Orders.
 */
class Order {

	/**
	 * Faire order data
	 *
	 * @var object
	 */
	protected object $order_data;

	/**
	 * Class constructor.
	 *
	 * @param object $order_data Faire order data.
	 */
	public function __construct( object $order_data ) {
		$this->order_data = $order_data;
	}

	/**
	 * Retrieves the Faire order ID.
	 *
	 * @return string The Faire order ID.
	 */
	public function get_id(): string {
		return $this->order_data->id;
	}

	/**
	 * Retrieves the Faire order status.
	 *
	 * @return string The Faire order status.
	 */
	public function get_state(): string {
		return strtoupper( $this->order_data->state );
	}

	/**
	 * Retrieves the Faire order items.
	 *
	 * @return array The Faire order items.
	 */
	public function get_items(): array {
		return $this->order_data->items;
	}

	/**
	 * Retrieves the Faire order address.
	 *
	 * @return object The Faire order address.
	 */
	public function get_address(): object {
		return $this->order_data->address;
	}

	/**
	 * Returns the currency used in a given Faire order.
	 *
	 * @return string The order currency.
	 */
	public function get_order_currency(): string {
		$order_items = $this->get_items();
		if ( 0 === count( $order_items ) ) {
			return '';
		}
		return $order_items[0]->price->currency ?? '';
	}

	/**
	 * Checks if an order was already synced.
	 *
	 * @param array $synced_orders List of synced orders.
	 *
	 * @return bool True if the order was synced.
	 */
	public function check_was_synced( array $synced_orders ): bool {
		return isset( array_flip( $synced_orders )[ $this->order_data->id ] );
	}

	/**
	 * Get brand_discounts.
	 *
	 * @return array
	 */
	public function get_discounts() {
		return isset( $this->order_data->brand_discounts ) ? $this->order_data->brand_discounts : array();
	}

}
