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
	exit;
} // Exit if accessed directly.

if ( ! class_exists( 'YITH_PayPal_EC_Response_Payment' ) ) {
	/**
	 * Class YITH_PayPal_EC_Response_Payment
	 */
	class YITH_PayPal_EC_Response_Payment extends YITH_PayPal_EC_Response {

		/**
		 * Success payments.
		 *
		 * @var array
		 */
		protected $success_payments = array( 'Completed', 'Processed', 'In-Progress' );

		/**
		 * Prefix
		 *
		 * @var string
		 */
		protected $prefix = 'PAYMENTINFO_0_';


		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.2.0
		 *
		 * @param array  $response Response.
		 * @param string $prefix Prefix.
		 */
		public function __construct( $response, $prefix = 'PAYMENTINFO_0_' ) {
			parent::__construct( $response );
		}


		/**
		 * Checks if the transaction was approved
		 *
		 * @return bool
		 * @since  1.2.0
		 */
		public function transaction_approved() {
			return in_array( $this->get_response_payment_parameter( 'PAYMENTSTATUS' ), $this->success_payments, true );
		}

		/**
		 * Returns the name for this
		 *
		 * @param string $name Name.
		 *
		 * @return string
		 */
		protected function get_response_payment_parameter_name( $name ) {
			return $this->prefix . $name;
		}

		/**
		 * Returns a payment parameter
		 *
		 * @param string $name Name.
		 *
		 * @return string
		 */
		public function get_response_payment_parameter( $name ) {
			$name = $this->get_response_payment_parameter_name( $name );

			return $this->get_response_parameter( $name );
		}

		/**
		 * Returns the BILLINGAGREEMENTID parameter of the response
		 * after  get_express_checkout_details request
		 *
		 * @return int|mixed
		 */
		public function get_payment_status() {
			return isset( $this->response_parameters['BILLINGAGREEMENTID'] ) ? $this->response_parameters['BILLINGAGREEMENTID'] : 0;
		}
	}

}

/**
 * Implements  Class
 *
 * @class   YWSBS_PayPal_EC_Payment_Response_Reference_Transaction
 * @package YITH WooCommerce Subscription
 * @since   1.2.0
 */
if ( ! class_exists( 'YITH_PayPal_EC_Response_Payment_Reference_Transaction' ) ) {
	/**
	 * Class YITH_PayPal_EC_Response_Payment_Reference_Transaction
	 */
	class YITH_PayPal_EC_Response_Payment_Reference_Transaction extends YITH_PayPal_EC_Response_Payment { //phpcs:ignore

		/**
		 * Prefix
		 *
		 * @var string
		 */
		protected $prefix = '';

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used.
		 *
		 * @since  1.2.0
		 *
		 * @param array $response Response.
		 */
		public function __construct( $response ) { //phpcs:ignore
			parent::__construct( $response );
		}

	}

}

