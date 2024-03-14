<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Implements helper functions for YITH Pre-Order for Woocommerce
 *
 * @since   2.0.0
 * @package YITH\PreOrder
 */

if ( ! function_exists( 'ywpo_get_pre_order' ) ) {

	/**
	 * Get the YITH_Pre_Order_Product object from a WC Product.
	 *
	 * @param WC_Product|string|int $product The WC_Product object or product ID.
	 *
	 * @return YITH_Pre_Order_Product|bool
	 */
	function ywpo_get_pre_order( $product ) {
		// Use the WC product factory function to ensure that $product is a WC_Product object.
		$product = wc_get_product( $product );
		if ( ! $product instanceof WC_Product ) {
			return false;
		}
		return new YITH_Pre_Order_Product( $product );
	}
}

if ( ! function_exists( 'ywpo_cart_contains_pre_order_product' ) ) {

	/**
	 * Check if the WC_Cart contains any pre-order product.
	 *
	 * @return bool
	 */
	function ywpo_cart_contains_pre_order_product() {
		if ( WC()->cart && did_action( 'wp_loaded' ) ) {
			foreach ( WC()->cart->get_cart() as $item ) {
				if ( YITH_Pre_Order()::is_pre_order_active( $item['data'] ) ) {
					return true;
				}
			}
		}
		return false;
	}
}

if ( ! function_exists( 'ywpo_count_pre_order_items_on_cart' ) ) {
	/**
	 * Counts how many pre-order items are in the current WC_Cart.
	 *
	 * @since  2.0.0
	 *
	 * @return int
	 */
	function ywpo_count_pre_order_items_on_cart() {
		$counter = 0;
		if ( WC()->cart ) {
			foreach ( WC()->cart->get_cart() as $item ) {
				if ( YITH_Pre_Order()::is_pre_order_product( $item['data'] ) ) {
					$counter += $item['quantity'];
				}
			}
		}
		return $counter;
	}
}

if ( ! function_exists( 'ywpo_count_pre_order_items_on_order' ) ) {
	/**
	 * Counts how many pre-order items are in the provided WC_Order object.
	 *
	 * @param WC_Order|string|int $order The WC_Order object or order ID.
	 * @return int
	 */
	function ywpo_count_pre_order_items_on_order( $order ) {
		$order = wc_get_order( $order );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}

		return $order->get_meta( '_ywpo_pre_order_items' ) ? count( $order->get_meta() ) : false;
	}
}

if ( ! function_exists( 'ywpo_order_has_pre_order' ) ) {
	/**
	 * Checks if the order contains any Pre-Order product.
	 *
	 * @param mixed $order The order ID or the WC_Order object.
	 *
	 * @return bool
	 */
	function ywpo_order_has_pre_order( $order ) {
		$order = wc_get_order( $order );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}
		return 'yes' === $order->get_meta( '_order_has_preorder' );
	}
}

if ( ! function_exists( 'ywpo_get_orders_by_customer' ) ) {
	/**
	 * Get an array of IDs of all the orders with pre-order products made by a specific customer.
	 *
	 * @param int $customer_id The customer ID.
	 *
	 * @return array
	 */
	function ywpo_get_orders_by_customer( $customer_id ) {
		$statuses          = wc_get_order_statuses();
		$excluded_statuses = apply_filters( 'ywpo_get_orders_by_customer_excluded_statuses', array( 'wc-cancelled', 'wc-refunded', 'wc-failed' ) );
		foreach ( $excluded_statuses as $excluded_status ) {
			unset( $statuses[ $excluded_status ] );
		}

		$args = array(
			'status'             => array_keys( $statuses ),
			'customer_id'        => $customer_id,
			'order_has_preorder' => 'yes',
			'return'             => 'ids',
			'limit'              => -1,
		);

		$orders = wc_get_orders( $args );

		return apply_filters( 'ywpo_get_orders_by_customer', $orders, $customer_id );
	}
}

if ( ! function_exists( 'ywpo_is_admin' ) ) {
	/**
	 * Checks if the order contains any Pre-Order product.
	 *
	 * @return bool
	 */
	function ywpo_is_admin() {
		return is_admin() || defined( 'DOING_CRON' ) && DOING_CRON;
	}
}

if ( ! function_exists( 'ywpo_print_date' ) ) {
	/**
	 * Print a formatted day by a given timestamp.
	 *
	 * @param int    $timestamp The date in timestamp format.
	 * @param string $format The format to print the date.
	 * @return string
	 */
	function ywpo_print_date( $timestamp, $format = '' ) {
		$default_format = apply_filters( 'ywpo_default_date_format', get_option( 'date_format' ) );
		if ( ! $format ) {
			$format = $default_format;
		}
		$date = '';
		try {
			$date = new WC_DateTime( get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $timestamp ) ) );
		} catch ( Exception $e ) {
			new WP_Error( $e->getCode(), $e->getMessage() );
		}
		return $date ? $date->date_i18n( $format ) : get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $timestamp ), $format );
	}
}

if ( ! function_exists( 'ywpo_print_time' ) ) {
	/**
	 * Print a formatted time by a given timestamp.
	 *
	 * @param int    $timestamp The date in timestamp format.
	 * @param string $format The format to print the time.
	 * @return string
	 */
	function ywpo_print_time( $timestamp, $format = '' ) {
		$default_format = apply_filters( 'ywpo_default_time_format', get_option( 'time_format' ) );
		if ( ! $format ) {
			$format = $default_format;
		}
		return ywpo_print_date( (int) $timestamp, $format );
	}
}

if ( ! function_exists( 'ywpo_print_datetime' ) ) {
	/**
	 * Print a formatted day and time by a given timestamp.
	 *
	 * @param int    $timestamp The date in timestamp format.
	 * @param string $format The format to print the date.
	 *
	 * @return string
	 */
	function ywpo_print_datetime( $timestamp, $format = '' ) {
		$default_format = apply_filters( 'ywpo_default_datetime_format', get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
		if ( ! $format ) {
			$format = $default_format;
		}
		return ywpo_print_date( (int) $timestamp, $format );
	}
}

if ( ! function_exists( 'ywpo_get_timezone_offset_label' ) ) {
	/**
	 * Check if the order is paid.
	 *
	 * @return bool
	 */
	function ywpo_get_timezone_offset_label() {
		$gmt_offset = get_option( 'gmt_offset' );
		if ( 0 <= $gmt_offset ) {
			$offset_name = '+' . $gmt_offset;
		} else {
			$offset_name = (string) $gmt_offset;
		}
		$offset_name = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $offset_name );
		return apply_filters( 'ywpo_get_time_offset', 'UTC' . $offset_name, $offset_name, $gmt_offset );
	}
}

if ( ! function_exists( 'ywpo_print_datetime_and_offset' ) ) {
	/**
	 * Print a formatted day by a given timestamp along with the timezone offset.
	 *
	 * @param int    $timestamp The date in timestamp format.
	 * @param string $format The format to print the date.
	 * @return bool
	 */
	function ywpo_print_datetime_and_offset( $timestamp, $format = '' ) {
		return apply_filters( 'ywpo_print_datetime_and_offset', ywpo_print_datetime( (int) $timestamp, $format ) . ' (' . ywpo_get_timezone_offset_label() . ')' );
	}
}

if ( ! function_exists( 'ywpo_get_release_date' ) ) {
	/**
	 * Print a formatted day by a given timestamp along with the timezone offset.
	 *
	 * @param WC_Product|string|int $product The WC_Product object or the product ID.
	 * @return int The release date in timestamp format.
	 */
	function ywpo_get_release_date( $product ) {
		$product = wc_get_product( $product );
		return apply_filters( 'ywpo_get_release_date', $product instanceof WC_Product ? $product->get_meta( '_ywpo_for_sale_date' ) : 0 );
	}
}

if ( ! function_exists( 'ywpo_availability_date_not_ready' ) ) {
	/**
	 * Check if the availability date is not ready.
	 *
	 * @param WC_Product $product The product WC_Product object.
	 * @return bool
	 */
	function ywpo_availability_date_not_ready( $product ) {
		$return = false;

		$pre_order  = ywpo_get_pre_order( $product );
		$start_mode = $pre_order->get_start_mode();

		if ( 'date' === $start_mode ) {
			$timestamp = $pre_order->get_start_date_timestamp();
			if ( $timestamp > time() ) {
				$return = true;
			}
		}

		return $return;
	}
}

if ( ! function_exists( 'ywpo_reset_product' ) ) {
	/**
	 * Delete the pre-order post meta from the product.
	 *
	 * @param WC_Product $product The WC_Order object.
	 */
	function ywpo_reset_pre_order( $product ) {
		$id    = $product->get_id();
		$metas = apply_filters(
			'ywpo_reset_pre_order_product_post_meta',
			array(
				'_ywpo_preorder',
				'_ywpo_start_mode',
				'_ywpo_start_date',
				'_ywpo_start_date_label',
				'_ywpo_availability_date_mode',
				'_ywpo_for_sale_date',
				'_ywpo_dynamic_availability_date',
				'_ywpo_price_mode',
				'_ywpo_max_qty_enabled',
				'_ywpo_max_qty',
				'_ywpo_override_labels',
				'_ywpo_preorder_label',
				'_ywpo_preorder_availability_date_label',
				'_ywpo_preorder_no_date_label',
				'_ywpo_override_fee',
				'_ywpo_fee',
				'_ywpo_override_charge_type',
				'_ywpo_charge_type',
				'_ywpo_preorder_price',
				'_ywpo_preorder_discount_percentage',
				'_ywpo_preorder_discount_fixed',
				'_ywpo_preorder_increase_percentage',
				'_ywpo_preorder_increase_fixed',
			),
			$product
		);

		foreach ( $metas as $meta ) {
			delete_post_meta( $id, $meta );
		}

		do_action( 'yith_ywpo_clear_pre_order_product', $product );
	}
}
