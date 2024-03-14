<?php

/**
 * WooCommerce Plugin Compatibility
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to http://www.skyverge.com
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2013, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'BWF_WC_Compatibility' ) ) :

	/**
	 * WooCommerce Compatibility Utility Class
	 *
	 * The unfortunate purpose of this class is to provide a single point of
	 * compatibility functions for dealing with supporting multiple versions
	 * of WooCommerce.
	 *
	 * The recommended procedure is to rename this file/class, replacing "my plugin"
	 * with the particular plugin name, so as to avoid clashes between plugins.
	 * Over time we expect to remove methods from this class, using the current
	 * ones directly, as support for older versions of WooCommerce is dropped.
	 *
	 * Current Compatibility: 2.0.x - 2.1
	 *
	 * @version 1.0
	 */
	#[AllowDynamicProperties]
	class BWF_WC_Compatibility {

		/**
		 * Compatibility function for outputting a woocommerce attribute label
		 *
		 * @param string $label the label to display
		 *
		 * @return string the label to display
		 * @since 1.0
		 *
		 */
		public static function wc_attribute_label( $label ) {
			return wc_attribute_label( $label );
		}

		public static function wc_attribute_taxonomy_name( $name ) {
			return wc_attribute_taxonomy_name( $name );
		}

		public static function wc_get_attribute_taxonomies() {
			return wc_get_attribute_taxonomies();
		}

		public static function wc_placeholder_img_src() {
			return wc_placeholder_img_src();
		}

		/**
		 * @param WC_Product $product
		 *
		 * @return string
		 */
		public static function woocommerce_get_formatted_product_name( $product ) {
			if ( ! $product instanceof WC_Product ) {
				return __( 'No title', 'woofunnels' );
			}

			return $product->get_formatted_name();
		}

		/**
		 * @param $order
		 * @param $item
		 *
		 * @return WC_Product
		 */
		public static function get_product_from_item( $order, $item ) {
			return $item->get_product();
		}

		public static function get_short_description( $product ) {
			if ( $product === false ) {
				return '';
			}

			return apply_filters( 'woocommerce_short_description', $product->get_short_description() );
		}

		public static function get_productname_from_item( $order, $item ) {
			return $item->get_name();
		}

		public static function get_qty_from_item( $order, $item ) {
			return $item->get_quantity();
		}

		public static function get_display_item_meta( $order, $item ) {
			wc_display_item_meta( $item );
		}

		public static function get_display_item_downloads( $order, $item ) {
			wc_display_item_downloads( $item );
		}

		public static function get_purchase_note( $product ) {
			return $product ? $product->get_purchase_note() : '';
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed|string
		 */
		public static function get_order_currency( $order ) {
			return $order instanceof WC_Order ? $order->get_currency() : get_woocommerce_currency();
		}

		public static function get_payment_gateway_from_order( $order ) {
			return $order->get_payment_method();
		}

		public static function get_item_subtotal( $order, $item ) {
			return $item->get_subtotal();
		}

		public static function get_shipping_country_from_order( $order ) {
			return $order->get_shipping_country();
		}

		public static function get_billing_country_from_order( $order ) {
			return $order->get_billing_country();
		}

		public static function get_order_id( $order ) {
			if ( ! $order instanceof WC_Order ) {
				return $order;
			}

			return $order->get_id();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_billing_1( $order ) {
			return $order->get_billing_address_1();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_data( $order, $key ) {
			if ( method_exists( $order, 'get_' . $key ) ) {
				return call_user_func( array( $order, 'get_' . $key ) );
			}

			return self::get_order_meta( $order, $key );
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_billing_first_name( $order ) {
			return $order->get_billing_first_name();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_billing_last_name( $order ) {
			return $order->get_billing_last_name();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_status( $order ) {
			$status = $order->get_status();

			return ( strpos( $status, 'wc-' ) === false ) ? 'wc-' . $status : $status;
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_billing_2( $order ) {
			return $order->get_billing_address_2();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_shipping_1( $order ) {
			return $order->get_shipping_address_1();
		}

		/**
		 * Returns true if the installed version of WooCommerce is 2.6 or greater
		 *
		 * @return boolean true if the installed version of WooCommerce is 2.1 or greater
		 * @since 1.0
		 */
		public static function is_wc_version_gte_3_7() {
			return version_compare( self::get_wc_version(), '3.7.0', 'ge' );
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_used_coupons( $order ) {
			return $order->get_coupon_codes();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_shipping_total( $order ) {
			return $order->get_shipping_total();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_shipping_2( $order ) {
			return $order->get_shipping_address_2();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_date( $order ) {
			return $order->get_date_created();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_payment_method( $order ) {
			return $order->get_payment_method_title();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_ip_address( $order ) {
			return $order->get_customer_ip_address();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_note( $order ) {
			return $order->get_customer_note();
		}

		/**
		 * @param $date
		 * @param string $format
		 *
		 * @return string
		 */
		public static function get_formatted_date( $date, $format = '' ) {
			if ( empty( $format ) ) {
				$format = get_option( 'date_format' );
			}

			return wc_format_datetime( $date, $format );
		}

		/**
		 * Compatibility function to add and store a notice
		 *
		 * @param string $message The text to display in the notice.
		 * @param string $notice_type The singular name of the notice type - either error, success or notice. [optional]
		 *
		 * @since 1.0
		 *
		 */
		public static function wc_add_notice( $message, $notice_type = 'success' ) {
			wc_add_notice( $message, $notice_type );
		}

		/**
		 * Prints messages and errors which are stored in the session, then clears them.
		 *
		 * @since 1.0
		 */
		public static function wc_print_notices() {
			wc_print_notices();
		}

		/**
		 * Compatibility function to queue some JavaScript code to be output in the footer.
		 *
		 * @param string $code javascript
		 *
		 * @since 1.0
		 *
		 */
		public static function wc_enqueue_js( $code ) {
			wc_enqueue_js( $code );
		}

		/**
		 * Sets WooCommerce messages
		 *
		 * @since 1.0
		 */
		public static function set_messages() {
			if ( ! self::is_wc_version_gte_2_1() ) {
				global $woocommerce;
				$woocommerce->set_messages();
			}
		}

		/**
		 * Returns a new instance of the woocommerce logger
		 *
		 * @return WC_Logger logger
		 * @since 1.0
		 */
		public static function new_wc_logger() {
			return new WC_Logger();
		}

		/**
		 * Format decimal numbers ready for DB storage
		 *
		 * Sanitize, remove locale formatting, and optionally round + trim off zeros
		 *
		 * @param float|string $number Expects either a float or a string with a decimal separator only (no thousands)
		 * @param mixed $dp number of decimal points to use, blank to use woocommerce_price_num_decimals, or false to avoid all rounding.
		 * @param boolean $trim_zeros from end of string
		 *
		 * @return string
		 * @since 1.0
		 *
		 */
		public static function wc_format_decimal( $number, $dp = false, $trim_zeros = false ) {
			return wc_format_decimal( $number, $dp, $trim_zeros );
		}

		/**
		 * Get the count of notices added, either for all notices (default) or for one particular notice type specified
		 * by $notice_type.
		 *
		 * @param string $notice_type The name of the notice type - either error, success or notice. [optional]
		 *
		 * @return int the notice count
		 * @since 1.0
		 *
		 */
		public static function wc_notice_count( $notice_type = '' ) {
			return wc_notice_count( $notice_type );
		}

		/**
		 * Compatibility function to use the new WC_Admin_Meta_Boxes class for the save_errors() function
		 *
		 * @return old save_errors function or new class
		 * @since 1.0-1
		 */
		public static function save_errors() {
			WC_Admin_Meta_Boxes::save_errors();
		}

		/**
		 * Compatibility function to get the version of the currently installed WooCommerce
		 *
		 * @return string woocommerce version number or null
		 * @since 1.0
		 */
		public static function get_wc_version() {

			// WOOCOMMERCE_VERSION is now WC_VERSION, though WOOCOMMERCE_VERSION is still available for backwards compatibility, we'll disregard it on 2.1+
			if ( defined( 'WC_VERSION' ) && WC_VERSION ) {
				return WC_VERSION;
			}
			if ( defined( 'WOOCOMMERCE_VERSION' ) && WOOCOMMERCE_VERSION ) {
				return WOOCOMMERCE_VERSION;
			}

			return null;
		}

		/**
		 * Returns the WooCommerce instance
		 *
		 * @return WooCommerce woocommerce instance
		 * @since 1.0
		 */
		public static function WC() {
			return WC();
		}

		/**
		 * Returns true if the WooCommerce plugin is loaded
		 *
		 * @return boolean true if WooCommerce is loaded
		 * @since 1.0
		 */
		public static function is_wc_loaded() {
			return class_exists( 'WooCommerce' );
		}

		/**
		 * Returns true if the installed version of WooCommerce is 2.1 or greater
		 *
		 * @return boolean true if the installed version of WooCommerce is 2.1 or greater
		 * @since 1.0
		 */
		public static function is_wc_version_gte_2_1() {

			// can't use gte 2.1 at the moment because 2.1-BETA < 2.1
			return self::is_wc_version_gt( '2.0.20' );
		}

		/**
		 * Returns true if the installed version of WooCommerce is 2.6 or greater
		 *
		 * @return boolean true if the installed version of WooCommerce is 2.1 or greater
		 * @since 1.0
		 */
		public static function is_wc_version_gte_2_6() {

			return version_compare( self::get_wc_version(), '2.6.0', 'ge' );
		}

		/**
		 * Returns true if the installed version of WooCommerce is 2.6 or greater
		 *
		 * @return boolean true if the installed version of WooCommerce is 2.1 or greater
		 * @since 1.0
		 */
		public static function is_wc_version_gte_3_0() {

			return version_compare( self::get_wc_version(), '3.0.0', 'ge' );
		}

		/**
		 * @param WC_Order_Item_Shipping $method
		 *
		 * @return string
		 */
		public static function get_method_id( $method ) {
			$method_id = $method->get_method_id();
			if ( empty( $method_id ) ) {
				return '';
			}

			$method_exp = explode( ':', $method_id );

			return $method_exp[0];
		}

		/**
		 * @param WC_Order_Item_Shipping $method
		 *
		 * @return string
		 */
		public static function get_instance_id( $method ) {
			$method_id = $method->get_method_id();
			if ( empty( $method_id ) ) {
				return '';
			}

			$method_exp = explode( ':', $method_id );
			if ( ! is_array( $method_exp ) ) {
				return '';
			}

			if ( 2 === count( $method_exp ) ) {
				return $method_exp[1];
			}
			if ( is_callable( array( $method, 'get_instance_id' ) ) ) {
				return $method->get_instance_id();
			}

			return '';
		}

		/**
		 * Returns true if the installed version of WooCommerce is greater than $version
		 *
		 * @param string $version the version to compare
		 *
		 * @return boolean true if the installed version of WooCommerce is > $version
		 * @since 1.0
		 *
		 */
		public static function is_wc_version_gt( $version ) {
			return self::get_wc_version() && version_compare( self::get_wc_version(), $version, '>' );
		}

		public static function display_prices_including_tax() {
			if ( version_compare( self::get_wc_version(), '3.3.0', 'ge' ) ) {
				return 'incl' === get_option( 'woocommerce_tax_display_cart' );
			}
		}

		/**
		 * Get order meta, checking if HPOS enabled
		 *
		 * @param $order
		 * @param $key
		 *
		 * @return array|mixed|string|null
		 */
		public static function get_order_meta( $order, $key = '' ) {
			if ( empty( $key ) ) {
				return '';
			}
			if ( ! $order instanceof WC_Abstract_Order ) {
				return '';
			}

			$meta_value = $order->get_meta( $key );
			if ( ! empty( $meta_value ) ) {
				return $meta_value;
			}

			if ( true === self::is_hpos_enabled() ) {
				global $wpdb;
				$meta_value = $wpdb->get_var( $wpdb->prepare( "SELECT `meta_value` FROM `{$wpdb->prefix}wc_orders_meta` WHERE `meta_key`=%s AND `order_id`=%d", $key, $order->get_id() ) );
			}

			if ( ! empty( $meta_value ) ) {
				return $meta_value;
			}

			return get_post_meta( $order->get_id(), $key, true );;
		}

		/**
		 * Checks if HPOS enabled
		 *
		 * @return bool
		 */
		public static function is_hpos_enabled() {

			return function_exists( 'wc_get_container' ) && ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && method_exists( '\Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() );
		}

	}

endif; // Class exists check
