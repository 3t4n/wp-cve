<?php
/**
 * WooCommerce PayPal Here Gateway
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
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace Automattic\WooCommerce\PayPal_Here;

use SkyVerge\WooCommerce\PluginFramework\v5_6_1 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * WooCommerce PayPal Here Sideloader class.
 *
 * @since 1.0.0
 */
class Sideloader_Response implements Framework\SV_WC_Payment_Gateway_API_Payment_Notification_Response {


	/** @var array response data */
	protected $data = array();


	/**
	 * Constructs the response.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data the response data
	 */
	public function __construct( $data ) {

		$this->data = $data;
	}


	/**
	 * Determines if the transaction was successful.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function transaction_approved() {

		$approved = true;
		$trans_id = $this->get_transaction_id();

		// a `Type` value of 'UNKNOWN' indicates a failed payment
		if ( 'UNKNOWN' === strtoupper( $this->get_payment_type() ) ) {
			$approved = false;
		}

		// check for the `InvoiceID` to start with 'INV'
		if ( 0 !== stripos( strtoupper( trim( $trans_id ) ), 'INV' ) ) {
			$approved = false;
		}

		return $approved;
	}


	/**
	 * Determines if the transaction was cancelled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function transaction_cancelled() {

		return ! $this->transaction_approved();
	}


	/**
	 * Determines if the transaction was held.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function transaction_held() {

		// no held transactions in PayPal Here
		return false;
	}


	/**
	 * Gets the order ID associated with this response.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_order_id() {

		return $this->get_data( 'order_id' );
	}


	/**
	 * Determines if this is an IPN response.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_ipn() {

		return false;
	}


	/**
	 * Gets the transaction ID associated with this response.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_transaction_id() {

		return $this->get_data( 'InvoiceId' );
	}


	/**
	 * Gets the amount added to the invoice as a tip from the PayPal Here app.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_tip() {

		// the `Tip` param is not currently being returned correctly from the PayPal Here app {JB 2018-10-12}
		return $this->get_data( 'Tip' );
	}


	/**
	 * Gets the grand total that was charged/collected via the PayPal Here app.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_total() {

		return $this->get_data( 'GrandTotal' );
	}


	/**
	 * Returns the payment type for this response.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_payment_type() {

		// at the moment the `Type` param is only functional on the latest version
		// of the PayPal Here app, so at least for some time, we should not do
		// much around this value, as people who haven't upgraded their app will
		// see a blank for this param. {JB 2018-11-08}
		return $this->get_data( 'Type' );
	}


	/**
	 * Returns the string representation of this request.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function to_string() {

		return print_r( $this->data, true );
	}


	/**
	 * Returns the string representation of this request with any and all
	 * sensitive elements masked or removed.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function to_string_safe() {

		return $this->to_string();
	}


	/**
	 * Gets a value from the response data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key data key
	 * @return string
	 */
	protected function get_data( $key ) {

		return ! empty( $this->data[ $key ] ) ? $this->data[ $key ] : '';
	}


	/** No-Op Methods *********************************************************/


	/**
	 * Returns the card PAN or checking account number, if available.
	 *
	 * The PayPal Here Sideloader does not return this.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_account_number() {

		return '';
	}


	/**
	 * Returns a message appropriate for a frontend user.
	 *
	 * PayPal Here is admin-only and does not return this.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_user_message() {

		return '';
	}


	/**
	 * Gets the response status message.
	 *
	 * Not returned by PayPal Here.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status_message() {

		return '';
	}


	/**
	 * Gets the response status code.
	 *
	 * Not returned by PayPal Here.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status_code() {

		return '';
	}


}
