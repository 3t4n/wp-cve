<?php
/**
 * Faire API Order functionality.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Api;

use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Order_Api extends Faire_Api {

  /**
	 * The API client instance.
	 *
	 * @var Client\Order_Client
	 */
	protected $api_client;

	/**
	 * Constructor.
	 *
	 * @since [*next-version*]
	 *
	 * @throws Exception If an error occurred while creating the API driver, auth or client.
	 */
	public function __construct() {
		parent::__construct( __NAMESPACE__ . '\Client\Order_Client' );
	}

	/**
	 * Get a single order
	 *
	 * @param string $id A string
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get_order( string $id ): object {
		return $this->api_client->get_order( $id );
	}

	/**
	 * Get orders
	 *
	 * @param array $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get_orders( array $args = array() ): object {
		return $this->api_client->get_orders( $args );
	}

	/**
	 * Accepts a Faire order.
	 *
	 * @param string $order_id An order ID.
	 *
	 * @throws Exception Throws an exception if request fails.
	 *
	 * @return array
	 *   Order data.
	 */
	public function accept_order( $order_id ) {
		return $this->api_client->accept_order( $order_id );
	}

	/**
	 * Gets a single order.
	 *
	 * @param array $args Arguments.
	 *
	 * @throws Exception Throws an exception if request fails.
	 *
	 * @return array
	 *   Order data.
	 */
	public function set_order_shipment( $args ) {
		return $this->api_client->set_order_shipment( $args );
	}

	/**
	 * Backorders products of a given order.
	 *
	 * @param array $args Arguments.
	 *
	 * @throws Exception Throws an exception if request fails.
	 *
	 * @return array
	 *   Order data.
	 */
	public function backorder_products( $args ) {
		return $this->api_client->backorder_products( $args );
	}

}
