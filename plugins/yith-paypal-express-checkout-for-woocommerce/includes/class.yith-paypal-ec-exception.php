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
 * Implements  Class
 *
 * @class   YITH_PayPal_EC_Exception
 * @package YITH WooCommerce Subscription
 * @since   1.2.0
 */
if ( ! class_exists( 'YITH_PayPal_EC_Exception' ) ) {

	/**
	 * Class YITH_PayPal_EC_Exception
	 */
	class YITH_PayPal_EC_Exception extends Exception {


		/**
		 * List of errors from PayPal API Response.
		 *
		 * @var array
		 */
		public $error_list;

		/**
		 * Unique identifier of PayPal transaction.
		 *
		 *  The ID unique to this response message. PayPal recommends you log this ID.
		 *
		 * @var string
		 */
		public $correlation_id;


		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used.
		 *
		 * @since  1.2.0
		 *
		 * @param mixed $response Response.
		 */
		public function __construct( $response ) {
			parent::__construct( __( 'An error occurred while calling the PayPal API.', 'yith-paypal-express-checkout-for-woocommerce' ) );

			$this->get_errors( $response );
		}

		/**
		 * Get errors
		 *
		 * @param YWSBS_PayPal_Express_Checkout_Response $response Response.
		 */
		protected function get_errors( $response ) {
			$errors = array();
			if ( is_string( $response ) ) {
				$this->message = $response;
				return;
			}

			$response_error = $response->get_response_parameters();
			foreach ( $response_error as $index => $value ) {

				if ( preg_match( '/^L_ERRORCODE(\d+)$/', $index, $matches ) ) {
					$errors[ $matches[1] ]['code'] = $value;
				} elseif ( preg_match( '/^L_SHORTMESSAGE(\d+)$/', $index, $matches ) ) {
					$errors[ $matches[1] ]['message'] = $value;
				} elseif ( preg_match( '/^L_LONGMESSAGE(\d+)$/', $index, $matches ) ) {
					$errors[ $matches[1] ]['long'] = $value;
				} elseif ( preg_match( '/^L_SEVERITYCODE(\d+)$/', $index, $matches ) ) {
					$errors[ $matches[1] ]['severity'] = $value;
				} elseif ( 'CORRELATIONID' === $index ) {
					$this->correlation_id = $value;
				}
			}

			$this->error_list = $errors;
			$error_messages   = array();
			foreach ( $errors as $value ) {
				// translators: 1. error code, 2. error message, 3.long description of error.
				$error_messages[] = sprintf( __( 'PayPal error (%1$s): %2$s %3$s', 'yith-paypal-express-checkout-for-woocommerce' ), $value['code'], $value['message'], isset( $value['long'] ) ? ' - ' . $value['long'] : '' );
				break;
			}

			if ( empty( $error_messages ) ) {
				$error_messages[] = __( 'An error occurred while calling the PayPal API.', 'yith-paypal-express-checkout-for-woocommerce' );
			}

			$this->message = implode( PHP_EOL, $error_messages );
		}
	}

}

