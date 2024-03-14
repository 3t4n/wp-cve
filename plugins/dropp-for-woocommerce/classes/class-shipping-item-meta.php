<?php
/**
 * Shipping item meta
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Components\Choose_Location_Button;
use Dropp\Components\Location_Picker;
use WC_Order_Item;
use WC_Order_Item_Shipping;
use WC_Shipping;
use WC_Shipping_Rate;
use WP_Error;

/**
 * Shipping item meta
 */
class Shipping_Item_Meta {

	/**
	 * Setup
	 */
	public static function setup(): void {
		// Add fields to the shipping rate.
		add_action( 'woocommerce_after_shipping_rate', __CLASS__ . '::choose_location_button', 10, 2 );

		// Save the fields during checkout.
		add_action( 'woocommerce_checkout_create_order_shipping_item', __CLASS__ . '::attach_item_meta' );

		// Validation that a location has been selected.
		add_action( 'woocommerce_after_checkout_validation', __CLASS__ . '::validate_location', 10, 2 );

		// Show the location name in the order totals.
		add_filter( 'woocommerce_order_item_get_method_title', __CLASS__ . '::get_order_item_title', 10, 2 );
	}

	/**
	 * Attach item meta
	 *
	 * @param WC_Order_Item_Shipping $item Shipping item.
	 */
	public static function attach_item_meta( WC_Order_Item_Shipping $item ): void {
		$location_data = WC()->session->get( 'dropp_session_location' );
		if (empty($location_data)) {
			return;
		}
		$location      = [
			'id'        => preg_replace( '/[^a-z\d\-]/', '', $location_data['id'] ),
			'name'      => $location_data['name'],
			'pricetype' => $location_data['pricetype'],
			'address'   => $location_data['address'],
		];
		$item->add_meta_data( 'dropp_location', $location, true );
	}

	/**
	 * Validate location
	 *
	 * @param array $data   Posted data.
	 * @param WP_Error $errors Error object.
	 */
	public static function validate_location( array $data, WP_Error $errors ): void {
		$shipping_methods    = $data['shipping_method'];
		if (! $shipping_methods) {
			return;
		}
		$validation_required = false;
		$instance_id         = 0;
		foreach ( $shipping_methods as $method_id ) {
			if ( preg_match( '/^dropp_is:(\d+)$/', $method_id, $matches ) ) {
				// Note: This validation is not needed for dropp_home.
				$validation_required = true;
				$instance_id = $matches[1];
			}
		}
		if ( ! $validation_required ) {
			// Dropp is not used. No validation needed.
			return;
		}
		$location_data = WC()->session->get( 'dropp_session_location' );
		if ( empty( $location_data ) ) {
			// Validation failed. No location was selected.
			$errors->add(
				'shipping',
				__( 'No location selected. Please select a location for Dropp', 'dropp-for-woocommerce' )
			);
		}
	}

	/**
	 * Get order item title
	 *
	 * @param string $title Title.
	 * @param WC_Order_Item $item  Order Item.
	 *
	 * @return string               New title.
	 */
	public static function get_order_item_title( string $title, WC_Order_Item $item ): string {
		global $wp;
		if ( empty( $wp->query_vars['order-received'] ) && ! did_action( 'woocommerce_email_header' ) ) {
			// Skip on any page except on the thank you page and in emails.
			return $title;
		}
		if ( 'shipping' !== $item->get_type() ) {
			return $title;
		}
		if ( ! in_array($item->get_method_id(), ['dropp_is', 'dropp_is_oca'] ) ) {
			return $title;
		}
		$location = $item->get_meta( 'dropp_location' );
		if ( empty( $location['name'] ) || ! is_string( $location['name'] ) ) {
			return $title;
		}
		return "{$title} ({$location['name']})";
	}

	/**
	 * Choose location button
	 *
	 * @param WC_Shipping_Rate $shipping_rate Shipping rate.
	 * @param mixed $index  Index.
	 */
	public static function choose_location_button( WC_Shipping_Rate $shipping_rate, $index ) {

		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		if ( ! in_array($shipping_rate->get_method_id(), ['dropp_is', 'dropp_is_oca'] ) ) {
			return;
		}

		$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

		if ( ! in_array( $shipping_rate->get_id(), $chosen_methods ) ) {
			return;
		}

		echo (new Location_Picker($shipping_rate))->render();
	}
}
