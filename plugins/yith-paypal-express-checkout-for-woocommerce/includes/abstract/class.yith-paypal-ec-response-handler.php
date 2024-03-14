<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Implements YITH_PayPal_EC_Response_Handler Class
 *
 * @class   YITH_PayPal_EC_Response_Handler
 * @package YITH
 * @since  1.0.0
 */
if ( ! class_exists( 'YITH_PayPal_EC_Response_Handler' ) ) {

	/**
	 * Class YITH_PayPal_EC_Response_Handler
	 */
	abstract class YITH_PayPal_EC_Response_Handler {
		/**
		 * Sandbox mode
		 *
		 * @var bool
		 */
		protected $sandbox = false;


		/**
		 * Get the order from the PayPal 'Custom' variable.
		 *
		 * @param  string $raw_custom JSON Data passed back by PayPal.
		 *
		 * @return bool|WC_Order object
		 */
		protected function get_paypal_order( $raw_custom ) {
			// We have the data in the correct format, so get the order.
			$custom = json_decode( $raw_custom );
			if ( $custom && is_object( $custom ) ) {
				$order_id  = $custom->order_id;
				$order_key = $custom->order_key;
			} else {
				// Nothing was found.
				// translators: json data.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Order ID and key were not found in "custom". %s', 'yith-paypal-express-checkout-for-woocommerce' ), $raw_custom ) );
				return false;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				// We have an invalid $order_id, probably because invoice_prefix has changed.
				$order_id = wc_get_order_id_by_order_key( $order_key );
				$order    = wc_get_order( $order_id );
			}

			if ( ! $order || $order->get_order_key() !== $order_key ) {
				// translators: json data.
				yith_paypal_ec()->ec->log_add_message( sprintf( __( 'Order keys do not match. %s', 'yith-paypal-express-checkout-for-woocommerce' ), $raw_custom ) );
				return false;
			}

			return $order;
		}

		/**
		 * Complete order, add transaction ID and note.
		 *
		 * @param  WC_Order $order Order object.
		 * @param  string   $txn_id Transaction ID.
		 * @param  string   $note Payment note.
		 */
		protected function payment_complete( $order, $txn_id = '', $note = '' ) {
			$order->add_order_note( $note );
			$order->payment_complete( $txn_id );
			WC()->cart->empty_cart();
		}

		/**
		 * Hold order and add note.
		 *
		 * @param  WC_Order $order Order object.
		 * @param  string   $reason Reason why the payment is on hold.
		 */
		protected function payment_on_hold( $order, $reason = '' ) {
			$order->update_status( 'on-hold', $reason );
			wc_reduce_stock_levels( $order->get_id() );
			WC()->cart->empty_cart();
		}

		/**
		 * Get PayPal order from invoice.
		 *
		 * @param string $invoice Invoice.
		 * @return bool|WC_Order|WC_Order_Refund
		 */
		protected function get_paypal_order_from_invoice( $invoice ) {
			$extract      = explode( '-', $invoice );
			$order_number = false;
			$order        = false;

			if ( is_array( $extract ) ) {
				$order_number = end( $extract );
			}

			if ( empty( $order_number ) ) {
				return false;
			}

			$query_args = array(
				'numberposts' => 1,
				'meta_key'    => '_order_number', //phpcs:ignore
				'meta_value'  => $order_number, //phpcs:ignore
				'post_type'   => 'shop_order',
				'post_status' => 'any',
				'fields'      => 'ids',
			);

			$posts            = get_posts( $query_args );
			list( $order_id ) = ! empty( $posts ) ? $posts : null;

			// order was found.
			if ( null !== $order_id ) {
				$order = wc_get_order( $order_id );
			}

			return $order;

		}
	}
}
