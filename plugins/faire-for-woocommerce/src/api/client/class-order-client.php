<?php

namespace Faire\Wc\Api\Client;

use Exception;

/**
 * The API order client for Faire.
 */
class Order_Client extends Api_Client {

	/**
	 * Get Order from API
	 * This endpoint retrieves a single order given an order ID.
	 *
	 * @param string $id The order ID.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_order( string $id ): object {

		// Prepare the request route and data.
		$route = sprintf( 'orders/%s', $id );

		// Send the request and get the response.
		$response = $this->get( $route );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Get Orders from API
	 *
	 * @param array $args Arguments to retrieve the orders.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_orders( array $args ): object {

		// Get a page of orders.
		$response = $this->get_page( 'orders', $args );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Accepts a Faire order for processing.
	 *
	 * See https://faire.github.io/external-api-v2-docs/#accept-an-order
	 *
	 * @param string $order_id The order ID.
	 *
	 * @return object
	 *
	 * @throws Exception
	 */
	public function accept_order( string $order_id ): object {
		// Prepare the request route and data.
		$route    = sprintf( 'orders/%s/processing', $order_id );
		$response = $this->put( $route );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Assigns a shipment carrier to an order.
	 *
	 * See https://faire.github.io/external-api-v2-docs/#shipments
	 *
	 * @param array $args Shipment data, as defined in the Faire API docs.
	 *
	 * @return object
	 *
	 * @throws Exception
	 */
	public function set_order_shipment( array $args ): object {
		// Prepare the request route and data.
		$route    = sprintf( 'orders/%s/shipments', $args['order_id'] );
		$shipment = array(
			'shipments' => array( $args ),
		);
		$response = $this->post( $route, $shipment );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Requests the backordering of products in a given order.
	 *
	 * See https://faire.github.io/external-api-v2-docs/#backordering-items
	 *
	 * @param array $args Products to backorder, as defined in the Faire API docs.
	 *
	 * @return object
	 *
	 * @throws Exception
	 */
	public function backorder_products( array $args ): object {
		// Prepare the request route and data.
		$route     = sprintf( 'orders/%s/items/availability', $args['order_id'] );
		$backorder = array(
			'availabilities' => $args['availabilities'],
		);
		$response  = $this->post( $route, $backorder );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

}
