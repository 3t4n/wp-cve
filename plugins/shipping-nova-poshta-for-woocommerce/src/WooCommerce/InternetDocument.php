<?php
/**
 * InternetDocument
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce;

use WC_Order;
use Exception;
use NovaPoshta\Main;
use NovaPoshta\Api\Api;
use WC_Order_Item_Shipping;
use NovaPoshta\Notice\Notice;
use NovaPoshta\Settings\Settings;
use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

/**
 * Class InternetDocument
 *
 * @package NovaPoshta\WooCommerce
 */
class InternetDocument {

	/**
	 * API for Nova Poshta
	 *
	 * @var Api
	 */
	private $api;

	/**
	 * Settings.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Notice
	 *
	 * @var Notice
	 */
	private $notice;

	/**
	 * InternetDocument constructor.
	 *
	 * @param Api      $api      API for Nova Poshta.
	 * @param Settings $settings Settings.
	 * @param Notice   $notice   Notice.
	 */
	public function __construct( Api $api, Settings $settings, Notice $notice ) {

		$this->api      = $api;
		$this->settings = $settings;
		$this->notice   = $notice;
	}

	/**
	 * Create internet document for WC_Order
	 *
	 * @param WC_Order $order WooCommerce order.
	 *
	 * @throws Exception Invalid DateTime.
	 */
	public function create( WC_Order $order ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$shipping_item = $this->find_shipping_method( $order->get_shipping_methods() );
		if ( ! $shipping_item ) {
			return;
		}

		if ( $shipping_item->get_meta( 'internet_document' ) ) {
			$this->notice->add( 'error', esc_html__( 'The invoice for this order was created earlier.', 'shipping-nova-poshta-for-woocommerce' ) );

			return;
		}

		if ( empty( $this->settings->city_id() ) || empty( $this->settings->warehouse_id() ) ) {
			$this->notice->add(
				'error',
				wp_kses(
					sprintf( /* translators: %s - url to the settings page. */
						__( 'You should fill in the sender information on <a href="%s">the settings page.</a>', 'shipping-nova-poshta-for-woocommerce' ),
						get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG . '&tab=sender' )
					),
					[
						'a' => [
							'href' => true,
						],
					]
				)
			);

			return;
		}

		$items = $order->get_items();
		if ( empty( $items ) ) {
			$this->notice->add( 'error', esc_html__( 'The order doesn\'t have a products', 'shipping-nova-poshta-for-woocommerce' ) );

			return;
		}

		$is_redelivery = $order->get_payment_method() === 'shipping_nova_poshta_for_woocommerce_cod';
		$prepayment    = $this->get_prepayment( $order );
		$total         = $this->settings->exclude_shipping_from_total() ?
			$order->get_total() :
			$order->get_total() - $order->get_shipping_total();

		$response = $this->api->internet_document(
			$order->get_shipping_first_name() ? $order->get_shipping_first_name() : $order->get_billing_first_name(),
			$order->get_shipping_last_name() ? $order->get_shipping_last_name() : $order->get_billing_last_name(),
			method_exists( $order, 'get_shipping_phone' ) && $order->get_shipping_phone() ? $order->get_shipping_phone() : $order->get_billing_phone(),
			$shipping_item->get_meta( 'city_id' ),
			$this->get_delivery_address( $shipping_item ),
			$this->get_delivery_type( $shipping_item ),
			$total,
			$this->get_weight( $order ),
			$this->get_volume( $order ),
			$is_redelivery ? $total - $prepayment : 0
		);

		if ( is_wp_error( $response ) ) {
			$message = sprintf(
				'<strong>%s</strong>',
				esc_html__( 'The invoice wasn\'t created because:', 'shipping-nova-poshta-for-woocommerce' )
			);

			$message .= ' ' . implode( ', ', $response->get_error_messages() );
			$this->notice->add( 'error', $message );

			return;
		}

		$shipping_item->add_meta_data( 'internet_document', $response, true );
		$shipping_item->save_meta_data();
		$this->notice->add( 'success', esc_html__( 'The invoice was successfully created', 'shipping-nova-poshta-for-woocommerce' ) );

		$order->add_order_note(
			esc_html__( 'Created an Internet document for Nova Poshta', 'shipping-nova-poshta-for-woocommerce' )
		);
	}

	/**
	 * Get prepayment price.
	 *
	 * @param WC_Order $order Current Order.
	 *
	 * @return int
	 */
	protected function get_prepayment( WC_Order $order ): int {

		return 0;
	}

	/**
	 * Get delivery address.
	 *
	 * @param WC_Order_Item_Shipping $shipping_item Current shipping method.
	 *
	 * @return array|mixed|string
	 */
	protected function get_delivery_address( WC_Order_Item_Shipping $shipping_item ): string {

		return $shipping_item->get_meta( 'warehouse_id' );
	}

	/**
	 * Get delivery type.
	 *
	 * @param WC_Order_Item_Shipping $shipping_item Current shipping method.
	 *
	 * @return string
	 */
	protected function get_delivery_type( WC_Order_Item_Shipping $shipping_item ): string {

		return 'warehouse';
	}

	/**
	 * Get weight for current order.
	 *
	 * @param WC_Order $order Current order.
	 *
	 * @return float
	 */
	protected function get_weight( WC_Order $order ): float {

		return 0;
	}

	/**
	 * Get volume for current order.
	 *
	 * @param WC_Order $order Current order.
	 *
	 * @return array
	 */
	protected function get_volume( WC_Order $order ): array {

		return [
			'width'  => 0.23,
			'length' => 0.16,
			'height' => 0.1,
		];
	}

	/**
	 * Find current shipping method
	 *
	 * @param array $shipping_methods List of shipping methods.
	 *
	 * @return WC_Order_Item_Shipping|null
	 */
	protected function find_shipping_method( array $shipping_methods ) {

		foreach ( $shipping_methods as $shipping_method ) {
			if ( NovaPoshta::ID === $shipping_method->get_method_id() ) {
				return $shipping_method;
			}
		}

		return null;
	}

}
