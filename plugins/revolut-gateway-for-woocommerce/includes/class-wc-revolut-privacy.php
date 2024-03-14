<?php
/**
 * Revolut privacy class.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      2.0.0
 */

if ( ! class_exists( 'WC_Abstract_Privacy' ) ) {
	return;
}

/**
 * WC_Gateway_Revolut_Privacy class.
 */
class WC_Revolut_Privacy extends WC_Abstract_Privacy {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( __( 'Revolut', 'revolut-gateway-for-woocommerce' ) );

		$this->add_exporter(
			'revolut-gateway-for-woocommerce-order-data',
			__( 'WooCommerce Revolut Order Data', 'revolut-gateway-for-woocommerce' ),
			array(
				$this,
				'order_data_exporter',
			)
		);

		$this->add_eraser(
			'revolut-gateway-for-woocommerce-order-data',
			__( 'WooCommerce Revolut Data', 'revolut-gateway-for-woocommerce' ),
			array(
				$this,
				'order_data_eraser',
			)
		);
	}

	/**
	 * Returns a list of orders that are using one of Revolut's payment methods.
	 *
	 * @param string $email_address Email address.
	 * @param int    $page page.
	 *
	 * @return array WP_Post
	 */
	protected function get_revolut_orders( $email_address, $page ) {
		$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

		$order_query = array(
			'payment_method' => 'revolut',
			'limit'          => 10,
			'page'           => $page,
		);

		if ( $user instanceof WP_User ) {
			$order_query['customer_id'] = (int) $user->ID;
		} else {
			$order_query['billing_email'] = $email_address;
		}

		return wc_get_orders( $order_query );
	}

	/**
	 * Get privacy message
	 *
	 * @return string
	 */
	public function get_privacy_message() {
		/* translators:%1s: %$2s: */
		return wpautop( sprintf( __( 'By using this extension, you may be storing personal data or sharing data with an external service. %1$sLearn more about how this works, including what you may want to include in your privacy policy.%2$s', 'revolut-gateway-for-woocommerce' ), '<a href="https://docs.woocommerce.com/document/privacy-payments/#revolut-gateway-for-woocommerce" target="_blank">', '</a>' ) );
	}

	/**
	 * Handle exporting data for Orders.
	 *
	 * @param string $email_address E-mail address to export.
	 * @param int    $page Pagination of data.
	 *
	 * @return array
	 */
	public function order_data_exporter( $email_address, $page = 1 ) {
		$done           = false;
		$data_to_export = array();

		$orders = $this->get_revolut_orders( $email_address, (int) $page );

		$done = true;

		if ( 0 < count( $orders ) ) {
			foreach ( $orders as $order ) {
				$wc_order         = $this->wc_get_order( $order->get_id() );
				$data_to_export[] = array(
					'group_id'    => 'woocommerce_orders',
					'group_label' => __( 'Orders', 'revolut-gateway-for-woocommerce' ),
					'item_id'     => 'order-' . $order->get_id(),
					'data'        => array(
						array(
							'name'  => __( 'Revolut token', 'revolut-gateway-for-woocommerce' ),
							'value' => $wc_order->get_meta( '_revolut_pre_order_token', true ),
						),
					),
				);
			}

			$done = 10 > count( $orders );
		}

		return array(
			'data' => $data_to_export,
			'done' => $done,
		);
	}

	/**
	 * Finds and erases order data by email address.
	 *
	 * @param string $email_address The user email address.
	 * @param int    $page Page.
	 *
	 * @return array An array of personal data in name value pairs
	 */
	public function order_data_eraser( $email_address, $page ) {
		$orders = $this->get_revolut_orders( $email_address, (int) $page );

		$items_removed  = false;
		$items_retained = false;
		$messages       = array();

		foreach ( (array) $orders as $order ) {
			$order = wc_get_order( $order->get_id() );

			list($removed, $retained, $msgs) = $this->maybe_handle_order( $order );
			$items_removed                  |= $removed;
			$items_retained                 |= $retained;
			$messages                        = array_merge( $messages, $msgs );
		}

		// Tell core if we have more orders to work on still.
		$done = count( $orders ) < 10;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}

	/**
	 * Handle eraser of data tied to Orders
	 *
	 * @param WC_Order $wc_order WooCommerce Order.
	 *
	 * @return array
	 */
	protected function maybe_handle_order( $wc_order ) {
		$order_id      = $wc_order->get_id();
		$revolut_token = $wc_order->get_meta( '_revolut_pre_order_token', true );

		if ( empty( $revolut_token ) ) {
			return array( false, false, array() );
		}

		$wc_order->delete_meta_data( '_revolut_pre_order_token' );

		return array( true, false, array( __( 'Revolut Order Data Erased.', 'revolut-gateway-for-woocommerce' ) ) );
	}
}

new WC_Revolut_Privacy();
