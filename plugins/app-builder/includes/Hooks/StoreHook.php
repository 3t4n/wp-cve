<?php
namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

/**
 * Class StoreHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.7.0
 */
class StoreHook {

	/**
	 * StoreHook constructor.
	 */
	public function __construct() {
		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			array(
				$this,
				'update_order_from_request',
			),
			11,
			2
		);
	}

	/**
	 * Fires when the Checkout Store API updates an order from the API request data.
	 *
	 * @param \WC_Order        $order Order object.
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return void
	 */
	public function update_order_from_request( $order, $request ) {

		/**
		 * Handle billing shipping and additional fields from API request
		 */
		if ( ! function_exists( 'WOOCCM' ) ) {
			return;
		}

		$order_id = $order->get_id();

		/**
		 * Update billing address
		 */
		if ( count( $fields = WOOCCM()->billing->get_fields() ) ) {
			$this->store_meta_data( $order_id, $fields, $request->get_param( 'billing_address' ), '_billing_' );
		}

		/**
		 * Update shipping address
		 */
		if ( count( $fields = WOOCCM()->shipping->get_fields() ) ) {
			$this->store_meta_data( $order_id, $fields, $request->get_param( 'shipping_address' ), '_shipping_' );
		}

		/**
		 * Update additional fields
		 */
		if ( count( $fields = WOOCCM()->additional->get_fields() ) ) {
			$this->store_meta_data( $order_id, $fields, $request->get_param( 'additional' ), '_additional_' );
		}
	}

	/**
	 * Store meta data
	 *
	 * @param int   $order_id order id.
	 * @param array $fields fields.
	 * @param array $data data.
	 *
	 * @return void
	 */
	public function store_meta_data( $order_id, $fields, $data, $key_prefix = '' ) {
		foreach ( $fields as $field_id => $field ) {
			$key      = sprintf( '_%s', $field['key'] );
			$key_data = str_replace( $key_prefix, '', $key );

			if ( ! empty( $data[ $key_data ] ) ) {

				$value = $data[ $key_data ];

				if ( 'textarea' === $field['type'] ) {
					update_post_meta( $order_id, $key, wp_kses( $value, false ) );
				} elseif ( is_array( $value ) ) {
					update_post_meta( $order_id, $key, implode( ',', array_map( 'sanitize_text_field', $value ) ) );
				} else {
					update_post_meta( $order_id, $key, sanitize_text_field( $value ) );
				}
			}
		}
	}
}
