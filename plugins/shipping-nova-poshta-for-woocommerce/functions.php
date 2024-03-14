<?php
/**
 * Functions.
 *
 * @package Shipping-Nova-Poshta-For-Woocommerce
 */

use NovaPoshta\WooCommerce\Shipping\Methods\NovaPoshta\NovaPoshta;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Nova Poshta shipping method for the order.
 *
 * @param int $order_id Order ID.
 *
 * @return WC_Order_Item_Shipping|null
 */
function nova_poshta_order_get_shipping_method( int $order_id ) {

	$order = wc_get_order( $order_id );

	if ( empty( $order ) ) {
		return null;
	}

	$shipping_methods = $order->get_shipping_methods();

	foreach ( $shipping_methods as $shipping_method ) {
		if ( ! in_array( $shipping_method->get_method_id(), [ NovaPoshta::ID, NovaPoshta::ID . '_courier' ], true ) ) {
			continue;
		}

		return $shipping_method;
	}

	return null;
}

/**
 * Get shipping method meta.
 *
 * @param int    $order_id Order ID.
 * @param string $meta_key Meta key name.
 *
 * @return string
 */
function nova_poshta_get_shipping_method_meta( int $order_id, string $meta_key ) {

	$shipping_method = nova_poshta_order_get_shipping_method( $order_id );

	if ( ! $shipping_method ) {
		return '';
	}

	return (string) $shipping_method->get_meta( $meta_key );
}

/**
 * Get city ID from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_city_id( int $order_id ) {

	return nova_poshta_get_shipping_method_meta( $order_id, 'city_id' );
}

/**
 * Get city name from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_city_name( int $order_id ) {

	$city_id = nova_poshta_order_get_city_id( $order_id );

	if ( ! $city_id ) {
		return '';
	}

	$db = nova_poshta()->make( 'DB' );

	return (string) $db->city( $city_id );
}

/**
 * Get warehouse ID from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_warehouse_id( int $order_id ) {

	return nova_poshta_get_shipping_method_meta( $order_id, 'warehouse_id' );
}

/**
 * Get warehouse name from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_warehouse_name( int $order_id ) {

	$warehouse_id = nova_poshta_order_get_warehouse_id( $order_id );

	if ( ! $warehouse_id ) {
		return '';
	}

	$db = nova_poshta()->make( 'DB' );

	return $db->warehouse( $warehouse_id );
}

/**
 * Get courier address from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_courier_address( $order_id ) {

	return nova_poshta_get_shipping_method_meta( $order_id, 'courier_address' );
}

/**
 * Get internet document from order.
 *
 * @param int $order_id Order ID.
 *
 * @return string
 */
function nova_poshta_order_get_internet_document( $order_id ) {

	return nova_poshta_get_shipping_method_meta( $order_id, 'internet_document' );
}
