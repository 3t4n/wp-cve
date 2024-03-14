<?php
/**
 * User
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta;

/**
 * Class User
 *
 * @package NovaPoshta
 */
class User {

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'woocommerce_checkout_create_order_shipping_item', [ $this, 'update' ], 10, 4 );

		add_filter( 'shipping_nova_poshta_for_woocommerce_default_city_id', [ $this, 'city' ] );
		add_filter( 'shipping_nova_poshta_for_woocommerce_default_warehouse_id', [ $this, 'warehouse' ] );
	}

	/**
	 * Current user city.
	 *
	 * @param string $city_id City ID.
	 *
	 * @return string
	 */
	public function city( string $city_id ): string {

		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			return $city_id;
		}

		$user_city_id = get_user_meta( $user_id, 'shipping_nova_poshta_for_woocommerce_city', true );

		return ! empty( $user_city_id ) ? $user_city_id : $city_id;
	}

	/**
	 * Current user warehouse
	 *
	 * @param string $warehouse_id Warehouse ID.
	 *
	 * @return string
	 */
	public function warehouse( string $warehouse_id ): string {

		$user_id = get_current_user_id();
		if ( $user_id ) {
			$user_warehouse_id = get_user_meta( $user_id, 'shipping_nova_poshta_for_woocommerce_warehouse', true );
		}

		return ! empty( $user_warehouse_id ) ? $user_warehouse_id : $warehouse_id;
	}

	/**
	 * Update user_meta after each order complete
	 */
	public function update() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		if ( empty( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_key( $_POST['shipping_nova_poshta_for_woocommerce_nonce'] );

		if ( ! wp_verify_nonce( $nonce, Main::PLUGIN_SLUG . '-shipping' ) ) {
			return;
		}

		$city_id         = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_city'] ) ) : '';
		$warehouse_id    = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_warehouse'] ) ) : '';
		$courier_address = ! empty( $_POST['shipping_nova_poshta_for_woocommerce_courier_address'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_nova_poshta_for_woocommerce_courier_address'] ) ) : '';

		if ( $city_id ) {
			update_user_meta( $user_id, 'shipping_nova_poshta_for_woocommerce_city', $city_id );
		}

		if ( $warehouse_id ) {
			update_user_meta( $user_id, 'shipping_nova_poshta_for_woocommerce_warehouse', $warehouse_id );
		}

		if ( $courier_address ) {
			update_user_meta( $user_id, 'shipping_nova_poshta_for_woocommerce_courier', $courier_address );
		}
	}
}
