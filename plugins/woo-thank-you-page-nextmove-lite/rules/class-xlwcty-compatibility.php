<?php
defined( 'ABSPATH' ) || exit;

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

if ( ! class_exists( 'XLWCTY_Compatibility' ) ) :

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
	class XLWCTY_Compatibility {

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

		public static function woocommerce_get_formatted_product_name( $product ) {
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
			if ( false === $product ) {
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

		/**
		 * @param $order WC_Order
		 * @param $item
		 */
		public static function get_display_item_meta( $order, $item ) {
			wc_display_item_meta( $item );
		}

		/**
		 * @param $order WC_Order
		 * @param $item
		 */
		public static function get_display_item_downloads( $order, $item ) {
			wc_display_item_downloads( $item );
		}

		/**
		 * @param $product WC_Product
		 *
		 * @return mixed|string
		 */
		public static function get_purchase_note( $product ) {
			return $product ? $product->get_purchase_note() : '';
		}

		/**
		 * @param $order WC_Order
		 *
		 * @return mixed
		 */
		public static function get_payment_gateway_from_order( $order ) {
			return $order->get_payment_method();
		}

		/**
		 * @param $order WC_Order
		 * @param $item
		 *
		 * @return mixed
		 */
		public static function get_item_subtotal( $order, $item ) {
			return $item->get_subtotal();
		}

		/**
		 * @param $order WC_Order
		 *
		 * @return mixed
		 */
		public static function get_shipping_country_from_order( $order ) {
			return $order->get_shipping_country();
		}

		/**
		 * @param $order WC_Order
		 *
		 * @return mixed
		 */
		public static function get_billing_country_from_order( $order ) {
			return $order->get_billing_country();
		}

		public static function get_order_id( $order ) {
			return $order->get_id();
		}

		public static function get_product_parent_id( $product ) {
			return $product->get_parent_id();
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
			/** checking order instance **/
			if ( ! $order instanceof WC_Order ) {
				return __return_empty_string();
			}

			if ( method_exists( $order, 'get_' . $key ) ) {
				return call_user_func( array( $order, 'get_' . $key ) );
			}
			if ( method_exists( $order, 'get' . $key ) ) {
				return call_user_func( array( $order, 'get' . $key ) );
			}

			$data = $order->get_meta( $key );
			if ( ! empty( $data ) ) {
				return $data;
			}
			$data = $order->get_meta( '_' . $key );
			if ( ! empty( $data ) ) {
				return $data;
			}

			return __return_empty_string();
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_first_name( $order ) {
			$customer_first_name = $order->get_billing_first_name();
			if ( ! empty( $customer_first_name ) ) {
				return $customer_first_name;
			}
			$customer_first_name = $order->get_shipping_first_name();
			if ( ! empty( $customer_first_name ) ) {
				return $customer_first_name;
			}

			return '';
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_customer_last_name( $order ) {
			$customer_last_name = $order->get_billing_last_name();
			if ( ! empty( $customer_last_name ) ) {
				return $customer_last_name;
			}
			$customer_last_name = $order->get_shipping_last_name();
			if ( ! empty( $customer_last_name ) ) {
				return $customer_last_name;
			}

			return '';
		}

		/**
		 * @param WC_Order $order
		 *
		 * @return mixed
		 */
		public static function get_order_status( $order ) {
			$status = $order->get_status();

			if ( strpos( $status, 'wc-' ) === false ) {
				return 'wc-' . $status;
			} else {
				return $status;
			}
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
		}

		/**
		 * Returns a new instance of the woocommerce logger
		 *
		 * @return object logger
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
		 * @return: old save_errors function or new class
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
			return class_exists( 'Woocommerce' );
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

	}

endif; // Class exists check
