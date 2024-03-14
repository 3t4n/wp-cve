<?php
/**
 * Order
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
use WC_Meta_Data;
use WC_Order_Item;
use NovaPoshta\Main;
use NovaPoshta\Api\Api;
use WC_Order_Item_Shipping;
use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

/**
 * Class Order
 *
 * @package NovaPoshta\WooCommerce
 */
class Order {

	/**
	 * API for Nova Poshta
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * Internet document
	 *
	 * @var InternetDocument
	 */
	private $internet_document;

	/**
	 * Order constructor.
	 *
	 * @param Api              $api               API for Nova Poshta.
	 * @param InternetDocument $internet_document Internet document.
	 */
	public function __construct( Api $api, InternetDocument $internet_document ) {

		$this->api               = $api;
		$this->internet_document = $internet_document;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'woocommerce_checkout_create_order_shipping_item', [ $this, 'create' ], 10, 4 );
		add_action( 'woocommerce_checkout_update_customer', [ $this, 'update_nonce_for_new_users' ] );
		add_action( 'woocommerce_order_actions', [ $this, 'register_order_actions' ] );
		add_action(
			'woocommerce_order_action_nova_poshta_create_internet_document',
			[
				$this,
				'create_internet_document',
			]
		);
		add_action( 'woocommerce_before_order_itemmeta', [ $this, 'default_fields_for_shipping_item' ], 10, 2 );

		add_filter( 'woocommerce_order_item_display_meta_key', [ $this, 'labels' ], 10, 2 );
		add_filter( 'woocommerce_order_item_display_meta_value', [ $this, 'values' ], 10, 3 );
	}

	/**
	 * Update nonce for new user after login.
	 * Hack that allowed to us update user metadata after registered.
	 */
	public function update_nonce_for_new_users() {

		// phpcs:ignore
		$nonce = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ) ? sanitize_key( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ) : '';

		if ( $nonce ) {
			$_POST['shipping_nova_poshta_for_woocommerce_nonce'] = wp_create_nonce( Main::PLUGIN_SLUG . '-shipping' );
		}
	}

	/**
	 * Save shipping item
	 *
	 * @param WC_Order_Item_Shipping $item        Order shipping item.
	 * @param int                    $package_key Package key.
	 * @param array                  $package     Package.
	 * @param WC_Order               $order       Current order.
	 *
	 * @throws Exception Invalid DateTime.
	 */
	public function create( WC_Order_Item_Shipping $item, int $package_key, array $package, WC_Order $order ) {

		if ( empty( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_key( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] );

		if ( ! wp_verify_nonce( $nonce, Main::PLUGIN_SLUG . '-shipping' ) ) {
			return;
		}

		$this->save_meta_data( $item );
	}

	/**
	 * Save meta data.
	 *
	 * @param WC_Order_Item_Shipping $item Order shipping item.
	 */
	protected function save_meta_data( WC_Order_Item_Shipping $item ) {

		if ( NovaPoshta::ID !== $item->get_method_id() ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$city_id      = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ) : '';
		$warehouse_id = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( $city_id ) {
			$item->add_meta_data( 'city_id', $city_id, true );
		}

		if ( $warehouse_id ) {
			$item->add_meta_data( 'warehouse_id', $warehouse_id, true );
		}
	}

	/**
	 * Rename default labels
	 *
	 * @param string       $key  Label.
	 * @param WC_Meta_Data $meta Meta data.
	 *
	 * @return string
	 */
	public function labels( string $key, WC_Meta_Data $meta ): string {

		if ( 'city_id' === $meta->__get( 'key' ) ) {
			return esc_html__( 'City', 'shipping-nova-poshta-for-woocommerce' );
		}

		if ( 'warehouse_id' === $meta->__get( 'key' ) ) {
			return esc_html__( 'Warehouse', 'shipping-nova-poshta-for-woocommerce' );
		}

		if ( 'internet_document' === $meta->__get( 'key' ) ) {
			return esc_html__( 'Invoice', 'shipping-nova-poshta-for-woocommerce' );
		}

		if ( 'prepayment' === $meta->__get( 'key' ) ) {
			return esc_html__( 'Prepayment (Pro+)', 'shipping-nova-poshta-for-woocommerce' );
		}

		if ( 'weight' === $meta->__get( 'key' ) ) {
			return esc_html__( 'Weight (Pro+)', 'shipping-nova-poshta-for-woocommerce' );
		}

		return $key;
	}

	/**
	 * Rename default values
	 *
	 * @param string                 $value         Value.
	 * @param WC_Meta_Data           $meta          Meta data.
	 * @param WC_Order_Item_Shipping $shipping_item Shipping item.
	 *
	 * @return string
	 */
	public function values( string $value, WC_Meta_Data $meta, $shipping_item ): string {

		if ( 'city_id' === $meta->__get( 'key' ) && $meta->__get( 'value' ) ) {
			return $this->api->city( $meta->__get( 'value' ) );
		}

		if ( 'warehouse_id' === $meta->__get( 'key' ) && $meta->__get( 'value' ) ) {
			$city_id = $shipping_item->get_meta( 'city_id' );

			if ( ! $city_id ) {
				return $value;
			}

			return $this->api->warehouse( $city_id, $meta->__get( 'value' ) );
		}

		return $value;
	}

	/**
	 * Default fields for shipping item
	 *
	 * @param int           $item_id Item ID.
	 * @param WC_Order_Item $item    Item.
	 */
	public function default_fields_for_shipping_item( int $item_id, WC_Order_Item $item ) {

		if ( ! $item instanceof WC_Order_Item_Shipping ) {
			return;
		}

		if ( NovaPoshta::ID !== $item->get_method_id() ) {
			return;
		}

		if ( ! $item->get_meta( 'city_id' ) ) {
			$city = $this->api->cities( '', 1 );
			$item->update_meta_data( 'city_id', array_keys( $city )[0] );
		}
		if ( ! $item->get_meta( 'warehouse_id' ) ) {
			$city_id    = $item->get_meta( 'city_id' );
			$warehouses = $this->api->warehouses( $city_id );
			$item->update_meta_data( 'warehouse_id', array_keys( $warehouses )[0] );
		}

		$this->common_fields( $item );
		$item->save_meta_data();
	}

	/**
	 * Common fields for default and courier deliveries.
	 *
	 * @param WC_Order_Item $item Order Item.
	 *
	 * @return void
	 */
	protected function common_fields( WC_Order_Item $item ) {

		if ( ! $item->get_meta( 'prepayment' ) ) {
			$item->update_meta_data( 'prepayment', 0 );
		}

		if ( ! $item->get_meta( 'weight' ) ) {
			$item->update_meta_data( 'weight', 0 );
		}
	}

	/**
	 * Register actions
	 *
	 * @param array $actions List of actions.
	 *
	 * @return array
	 */
	public function register_order_actions( array $actions ): array {

		$actions['nova_poshta_create_internet_document'] = esc_html__( 'Create Nova Poshta Internet Document', 'shipping-nova-poshta-for-woocommerce' );

		return $actions;
	}

	/**
	 * Create internet document
	 *
	 * @param WC_Order $order Current order.
	 *
	 * @throws Exception Invalid DateTime.
	 */
	public function create_internet_document( WC_Order $order ) {

		$this->internet_document->create( $order );
	}
}
