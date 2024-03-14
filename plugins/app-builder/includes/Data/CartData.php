<?php


/**
 * class CartData
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Data;

defined( 'ABSPATH' ) || exit;

class CartData {

	/**
	 * @param $cart_key
	 *
	 * @return bool
	 */
	public function remove_cart_by_cart_key( $cart_key ): bool {

		if ( empty( $cart_key ) ) {
			return false;
		}

		global $wpdb;

		// Delete cart from database.
		$result = $wpdb->delete( $wpdb->prefix . constant( 'APP_BUILDER_CART_TABLE' ), array( 'cart_key' => $cart_key ) );

		// Delete the persistent cart permanently.
		if ( get_current_user_id() && apply_filters( 'woocommerce_persistent_cart_enabled', true ) ) {
			delete_user_meta( get_current_user_id(), '_woocommerce_persistent_cart_' . get_current_blog_id() );
		}

		return $result;
	}
}
