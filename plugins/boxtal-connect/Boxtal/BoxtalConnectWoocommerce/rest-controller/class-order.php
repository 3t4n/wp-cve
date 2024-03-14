<?php
/**
 * Contains code for the order class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Rest_Controller
 */

namespace Boxtal\BoxtalConnectWoocommerce\Rest_Controller;

use Boxtal\BoxtalConnectWoocommerce\Util\Api_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Auth_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Order_Item_Shipping_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Product_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Order_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Misc_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Order class.
 *
 * Opens API endpoint to sync orders.
 */
class Order {

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'/order',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'retrieve_orders_handler' ),
						'permission_callback' => array( $this, 'authenticate' ),
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'/order/(?P<order_id>[\d]+)/shipped',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'order_shipped_handler' ),
						'permission_callback' => array( $this, 'authenticate' ),
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'/order/(?P<order_id>[\d]+)/delivered',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'order_delivered_handler' ),
						'permission_callback' => array( $this, 'authenticate' ),
					)
				);
			}
		);
	}

	/**
	 * Call to auth helper class authenticate function.
	 *
	 * @param \WP_REST_Request $request request.
	 * @return \WP_Error|boolean
	 */
	public function authenticate( $request ) {
		return Auth_Util::authenticate_access_key( $request );
	}

	/**
	 * Retrieve orders callback.
	 *
	 * @void
	 */
	public function retrieve_orders_handler() {
		$response = $this->get_orders();
		Api_Util::send_api_response( 200, $response );
	}

	/**
	 * Get Woocommerce orders.
	 *
	 * @return array $result
	 */
	public function get_orders() {
		$result           = array();
		$statuses         = Order_Util::get_import_status_list();
		$current_language = get_locale();
		foreach ( wc_get_orders(
			array(
				'status' => array_keys( $statuses ),
				'limit'  => -1,
			)
		) as $order ) {
			$recipient = array(
				'firstname'    => Misc_Util::not_empty_or_null( Order_Util::get_shipping_first_name( $order ) ),
				'lastname'     => Misc_Util::not_empty_or_null( Order_Util::get_shipping_last_name( $order ) ),
				'company'      => Misc_Util::not_empty_or_null( Order_Util::get_shipping_company( $order ) ),
				'addressLine1' => Misc_Util::not_empty_or_null( Order_Util::get_shipping_address_1( $order ) ),
				'addressLine2' => Misc_Util::not_empty_or_null( Order_Util::get_shipping_address_2( $order ) ),
				'city'         => Misc_Util::not_empty_or_null( Order_Util::get_shipping_city( $order ) ),
				'state'        => Misc_Util::not_empty_or_null( Order_Util::get_shipping_state( $order ) ),
				'postcode'     => Misc_Util::not_empty_or_null( Order_Util::get_shipping_postcode( $order ) ),
				'country'      => Misc_Util::not_empty_or_null( Order_Util::get_shipping_country( $order ) ),
				'phone'        => Misc_Util::not_empty_or_null( Order_Util::get_billing_phone( $order ) ),
				'email'        => Misc_Util::not_empty_or_null( Order_Util::get_billing_email( $order ) ),
			);
			$products  = array();
			foreach ( $order->get_items( 'line_item' ) as $item ) {
				$product      = array();
				$variation_id = $item['variation_id'];
				$product_id   = ( '0' !== $variation_id && 0 !== $variation_id ) ? $variation_id : $item['product_id'];

				if ( ! Product_Util::is_product_virtual( $product_id ) ) {
					$product['weight']      = false !== Product_Util::get_product_weight( $product_id ) ? (float) Product_Util::get_product_weight( $product_id ) : null;
					$product['quantity']    = (int) $item['qty'];
					$product['price']       = Product_Util::get_product_price( $product_id );
					$product['description'] = array(
						$current_language => esc_html( Product_Util::get_product_description( $item ) ),
					);
					$products[]             = $product;
				}
			}

			$parcelpoint      = Order_Util::get_parcelpoint( $order );
			$status           = Order_Util::get_status( $order );
			$shipping_methods = $order->get_shipping_methods();
			$shipping_method  = ! empty( $shipping_methods ) ? array_shift( $shipping_methods ) : null;
			$result[]         = array(
				'internalReference' => '' . Order_Util::get_id( $order ),
				'reference'         => '' . Order_Util::get_order_number( $order ),
				'status'            => array(
					'key'          => $status,
					'translations' => array(
						$current_language => isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status,
					),
				),
				'shippingMethod'    => array(
					'key'          => Order_Item_Shipping_Util::get_method_id( $shipping_method ),
					'translations' => array(
						$current_language => Order_Item_Shipping_Util::get_name( $shipping_method ),
					),
				),
				'shippingAmount'    => Order_Util::get_shipping_total( $order ),
				'creationDate'      => Order_Util::get_date_created( $order ),
				'orderAmount'       => Order_Util::get_total( $order ),
				'recipient'         => $recipient,
				'products'          => $products,
				'parcelPoint'       => null === $parcelpoint ? null : array(
					'code'    => $parcelpoint->code,
					'network' => $parcelpoint->network,
				),
			);
		}
		return array( 'orders' => $result );
	}

	/**
	 * Order shipped handler callback.
	 *
	 * @param \WP_REST_Request $request request.
	 * @void
	 */
	public function order_shipped_handler( $request ) {
		$this->order_tracking_event_handler( $request, 'shipped' );
	}

	/**
	 * Order delivered handler callback.
	 *
	 * @param \WP_REST_Request $request request.
	 *
	 * @void
	 */
	public function order_delivered_handler( $request ) {
		$this->order_tracking_event_handler( $request, 'delivered' );
	}

	/**
	 * Order tracking event handler.
	 *
	 * @param \WP_REST_Request $request request.
	 * @param string           $type type of event (e.g. 'shipped' or 'delivered').
	 *
	 * @void
	 */
	public function order_tracking_event_handler( $request, $type ) {
		if ( ! isset( $request['order_id'] ) ) {
			Api_Util::send_api_response( 400 );
		}

		$order_id       = $request['order_id'];
		$order_statuses = wc_get_order_statuses();

		if ( 'shipped' === $type ) {
			$shipped_status = get_option( strtoupper( Branding::$branding_short ) . '_ORDER_SHIPPED', null );
			$order          = wc_get_order( $order_id );
			if ( false !== $order ) {
				$note = esc_html( __( 'Your order has been shipped.', 'boxtal-connect' ) );
				$order->add_order_note( $note, false );
				$order->save();

				/**
				 * Triggered when an order is shipped using this plugin
				 *
				 * @since 1.1.9
				 */
				do_action( 'boxtal_connect_order_shipped', $order_id );

				if ( null !== $shipped_status && isset( $order_statuses[ $shipped_status ] ) ) {
					$order->update_status( $shipped_status );
				} elseif ( null !== $shipped_status ) {
					update_option( strtoupper( Branding::$branding_short ) . '_ORDER_SHIPPED', null );
				}
			}
		}

		if ( 'delivered' === $type ) {
			$delivered_status = get_option( strtoupper( Branding::$branding_short ) . '_ORDER_DELIVERED', null );
			$order            = wc_get_order( $order_id );
			if ( false !== $order ) {
				$note = esc_html( __( 'Your order has been delivered.', 'boxtal-connect' ) );
				$order->add_order_note( $note, false );
				$order->save();

				/**
				 * Triggered when an order is delivered using this plugin
				 *
				 * @since 1.1.9
				 */
				do_action( 'boxtal_connect_order_delivered', $order_id );

				if ( null !== $delivered_status && isset( $order_statuses[ $delivered_status ] ) ) {
					$order->update_status( $delivered_status );
				} elseif ( null !== $delivered_status ) {
					update_option( strtoupper( Branding::$branding_short ) . '_ORDER_DELIVERED', null );
				}
			}
		}

		Api_Util::send_api_response( 200 );
	}
}
