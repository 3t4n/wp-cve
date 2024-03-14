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
 * WooCommerce PayPal Here Gateway main plugin class.
 *
 * @since 1.0.0
 */
class Gateway extends Framework\SV_WC_Payment_Gateway_Hosted {


	/** the protocol used to build sideload URLs */
	const PROTOCOL = 'paypalhere';

	/** the sideload API endpoint used by default */
	const ENDPOINT = 'takePayment';

	/** the endpoint used by default when an Android device is detected */
	const ANDROID_ENDPOINT = 'takePayment/v2';


	/** @var string prefix for invoice numbers */
	protected $invoice_prefix;

	/** @var string default order status for completed payments */
	protected $default_order_status;

	/** @var string whether debug logging is enabled or not */
	protected $debug_log;


	/**
	 * Constructs the gateway.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
			Plugin::GATEWAY_ID,
			wc_paypal_here(),
			array(
				'method_title'       => __( 'PayPal Here', 'woocommerce-gateway-paypal-here' ),
				'method_description' => __( 'PayPal Here allows sending orders to the PayPal Here app for point-of-sale payments.', 'woocommerce-gateway-paypal-here' ),
				'payment_type'       => 'multiple',
				'environments'       => array(
					self::ENVIRONMENT_PRODUCTION => esc_html_x( 'Production', 'software environment', 'woocommerce-gateway-paypal-here' ),
				),
			)
		);

		$this->title = __( 'PayPal Here', 'wooocommerce-gateway-paypal-here' );

		// remove PayPal Here from gateways available on the front-end
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'remove_gateway_from_frontend' ), 20 );

		// TODO: Remove this if/when the equivalent action in SV_WC_Payment_Gateway_Hosted becomes more namespace-friendly {JB 2018-10-11}
		add_action( 'woocommerce_api_' . $this->get_id() . '_response', array( $this, 'handle_transaction_response_request' ) );

		// redirect to the sideload URL, or a login screen if not currently logged-in
		add_action( 'woocommerce_api_' . $this->get_id() . '_sideload_redirect', array( $this, 'ajax_sideload_url_redirect' ) );
	}


	/**
	 * Returns the WC API URL for this gateway, based on the current protocol.
	 *
	 * TODO: Remove this if/when the parent method in SV_WC_Payment_Gateway_Hosted
	 * becomes more namespace-friendly {JB 2018-10-11}
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::get_transaction_response_handler_url()
	 *
	 * @since 1.0.0
	 *
	 * @return string the WC API URL for this server
	 */
	public function get_transaction_response_handler_url() {

		if ( $this->transaction_response_handler_url ) {
			return $this->transaction_response_handler_url;
		}

		$this->transaction_response_handler_url = add_query_arg( 'wc-api', $this->get_id() . '_response', home_url( '/' ) );

		// make ssl if needed
		if ( wc_checkout_is_https() ) {
			$this->transaction_response_handler_url = str_replace( 'http:', 'https:', $this->transaction_response_handler_url );
		}

		return $this->transaction_response_handler_url;
	}


	/**
	 * Returns true if currently doing a transaction response request.
	 *
	 * TODO: Remove this if/when the parent method in SV_WC_Payment_Gateway_Hosted
	 * becomes more namespace-friendly {JB 2018-10-11}
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::doing_transaction_response_handler()
	 *
	 * @since 1.0.0
	 *
	 * @return boolean true if currently doing a transaction response request
	 */
	public function doing_transaction_response_handler() {

		return isset( $_REQUEST['wc-api'] ) && ( $this->get_id() . '_response' ) === $_REQUEST['wc-api'];
	}


	/**
	 * Gets the URL to the action that will redirect to the sideload URL.
	 *
	 * @since 1.0.0
	 *
	 * @param int|null $order_id the order ID
	 * @return string
	 */
	public function get_sideload_redirect_url( $order_id = null ) {

		return add_query_arg( array(
			'wc-api'   => $this->get_id() . '_sideload_redirect',
			'order_id' => $order_id ? (int) $order_id : null,
		), home_url( '/' ) );
	}


	/**
	 * Redirects to the sideload URL for a given order,
	 * or to the login screen if not currently logged-in.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 *
	 * @return string|void
	 */
	public function ajax_sideload_url_redirect() {

		$order_id = isset( $_GET['order_id'] ) ? (int) $_GET['order_id'] : 0;

		try {

			if ( 0 === $order_id ) {
				throw new Framework\SV_WC_Payment_Gateway_Exception( __( 'No Order Specified.', 'woocommerce-gateway-paypal-here' ) );
			}

			if ( is_user_logged_in() ) {

				if ( ! current_user_can( 'edit_shop_orders' ) ) {
					throw new Framework\SV_WC_Payment_Gateway_Exception( __( 'Your account is not permitted to edit shop orders.', 'woocommerce-gateway-paypal-here' ) );
				}

				$order = wc_get_order( $order_id );

				if ( ! $order ) {
					throw new Framework\SV_WC_Payment_Gateway_Exception( __( 'Invalid Order.', 'woocommerce-gateway-paypal-here' ) );
				}

				if ( $order->is_paid() ) {
					throw new Framework\SV_WC_Payment_Gateway_Exception( __( 'Order has already been paid.', 'woocommerce-gateway-paypal-here' ) );
				}

				// logged-in, valid user with valid order -- go to the sideloader
				wp_redirect( $this->get_sideload_url( $order ) );

			} else {

				// not logged in -- redirect to login screen with immediate redirect to the sideload URL
				wp_redirect( wp_login_url( $this->get_sideload_redirect_url( $order_id ) ) );
			}

		} catch ( Framework\SV_WC_Payment_Gateway_Exception $exception ) {

			wp_send_json_error( $exception->getMessage() );
		}

		exit;
	}


	/**
	 * Overrides the default to remove unnecessary gateway fields.
	 *
	 * @see SV_WC_Payment_Gateway::init_form_fields()
	 *
	 * @since 1.0.0
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		unset( $this->form_fields['title'], $this->form_fields['description'], $this->form_fields['debug_mode'] );

		$this->form_fields['debug_log'] = array(
			'title'       => esc_html__( 'Debug Mode', 'woocommerce-gateway-paypal-here' ),
			'label'       => esc_html__( 'Enable Debug Mode', 'woocommerce-gateway-paypal-here' ),
			'type'        => 'checkbox',
			/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
			'description' => sprintf( esc_html__( 'Save Detailed Error Messages and API requests/responses to the %1$sdebug log%2$s', 'woocommerce-gateway-paypal-here' ), '<a href="' . Framework\SV_WC_Helper::get_wc_log_file_url( $this->get_id() ) . '">', '</a>' ),
			'default'     => 'no',
		);
	}


	/**
	 * Removes this gateway from frontend payment options.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 *
	 * @param array $available_gateways array of available gateways
	 * @return array
	 */
	public function remove_gateway_from_frontend( $available_gateways ) {

		unset( $available_gateways[ Plugin::GATEWAY_ID ] );

		return $available_gateways;
	}


	/**
	 * Overrides the return URL to go to the admin rather than the thank you page.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order Order object.
	 * @return string
	 */
	public function get_return_url( $order = null ) {

		$return_url = admin_url();

		if ( $order && $order->get_id() && current_user_can( 'edit_shop_orders' ) ) {

			$return_url = get_edit_post_link( $order->get_id(), 'url' );
		}

		return $return_url;
	}


	/**
	 * Processes the transaction response for the given order.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param Sideloader_Response $response transaction response
	 * @throws \Exception
	 */
	protected function process_transaction_response( $order, $response ) {

		if ( $response->transaction_approved() ) {

			$this->add_transaction_data( $order, $response );
			$this->complete_payment( $order, $response );
			$this->do_transaction_approved( $order, $response );

		} elseif ( $response->transaction_cancelled() ) {

			// cancelled in this context indicates that the transaction was not
			// completed inside the PayPal Here app in the same 'session' that
			// it was started. That doesn't necessarily mean the order is
			// cancelled, so we shouldn't set the cancelled status. Merchants
			// will likely return to the edit order screen and attempt sending
			// it to PayPal Here for payment again.

			/* translators: %1$s - payment gateway title */
			$order_note = sprintf( esc_html__( '%1$s Transaction Attempted and Cancelled', 'woocommerce-gateway-paypal-here' ), $this->get_method_title() );

			$order->add_order_note( $order_note );
			$this->do_transaction_cancelled( $order, $response );

		} else {

			$this->do_transaction_failed_result( $order, $response );
			$this->do_transaction_failed( $order, $response );
		}
	}


	/**
	 * Handle a cancelled transaction response.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param Sideloader_Response $response the response object
	 */
	protected function do_transaction_cancelled( \WC_Order $order, $response ) {

		wp_redirect( $this->get_return_url( $order ) );
		exit;
	}


	/**
	 * Handle a failed transaction response.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param Sideloader_Response $response the response object
	 */
	protected function do_transaction_failed( \WC_Order $order, $response ) {

		wp_redirect( $this->get_return_url( $order ) );
		exit;
	}


	/**
	 * Handle an invalid transaction response.
	 *
	 * i.e. the order has already been paid or was not found
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order Optional. The order object
	 * @param Sideloader_Response $response the response object
	 */
	protected function do_invalid_transaction_response( $order = null, $response ) {

		// our method override first ensures a non-null $response
		if ( $response && $response->is_ipn() ) {
			status_header( 200 );
			die();
		}

		if ( $order ) {
			wp_redirect( $this->get_return_url( $order ) );
			exit;
		}

		// use the admin URL given this isn't a customer-facing gateway
		wp_redirect( admin_url( 'edit.php?post_type=shop_order' ) );
		exit;
	}


	/**
	 * Gets a URL to collect payment for the given order via the sideloader.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @return string
	 */
	public function get_sideload_url( \WC_Order $order ) {

		$sideload_url = '';

		if ( $pay_page_url = $this->get_hosted_pay_page_url( $order ) ) {

			$pay_page_params = $this->get_hosted_pay_page_params( $order );
			$sideload_url    = add_query_arg( $pay_page_params, $pay_page_url );

			$this->log_sideload_url_request( $pay_page_url, $pay_page_params );
		}

		return $sideload_url;
	}


	/**
	 * Gets the hosted pay page url to redirect to, to allow the customer to
	 * remit payment.  This is generally the bare URL, without any query params.
	 *
	 * This method may be called more than once during a single request.
	 *
	 * @see SV_WC_Payment_Gateway_Hosted::get_hosted_pay_page_params()
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order optional order object, defaults to null
	 * @return string hosted pay page url, or false if it could not be determined
	 */
	public function get_hosted_pay_page_url( $order = null ) {

		return $this->get_sideload_protocol() . '://' . $this->get_sideload_endpoint();
	}


	/**
	 * Returns the payment types that are accepted via the sideloader.
	 *
	 * @since 1.0.0
	 *
	 * @return string[] payment types accepted
	 */
	public function get_sideload_payments_accepted() {

		/**
		 * Filters the accepted payment types for the PayPal Here Sideloader.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] payment types accepted
		 * @param Gateway gateway object instance
		 */
		$accepted = apply_filters( 'wc_' . $this->get_id() . '_sideload_payments_accepted', array(
			'cash',
			'card',
			'paypal',
		), $this );

		return is_array( $accepted ) ? $accepted : array( $accepted );
	}


	/**
	 * Returns the gateway hosted pay page parameters.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::get_hosted_pay_page_params()
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @return array associative array of name-value parameters
	 */
	public function get_hosted_pay_page_params( $order ) {

		/**
		 * Filters the parameters that are used to build the sideload URL.
		 *
		 * @since 1.0.0
		 *
		 * @param array sideload parameters
		 * @param \WC_Order $order the order object
		 */
		$params = apply_filters( 'wc_' . $this->get_id() . '_sideload_url_params', array(
			'accepted'   => implode( ',', $this->get_sideload_payments_accepted() ),
			'returnUrl'  => urlencode( add_query_arg( $this->get_return_url_params( $order ), $this->get_transaction_response_handler_url() ) ),
			'as'         => 'b64',
			'step'       => 'choosePayment',
			// this is documented as a valid param, but actually fails the order. leaving commented in case they fix in a future release {JB 2018-11-05}
			// 'payerPhone' => $order->get_billing_phone( 'edit' ),
			'invoice'    => base64_encode( json_encode( $this->generate_invoice( $order ) ) ),
		), $order );

		return $this->remove_empty_params( $params );
	}


	/**
	 * Gets params for the return notification URL.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @return array
	 */
	protected function get_return_url_params( $order ) {

		return array(
			'nonce'      => wp_create_nonce( 'wc_' . $this->get_id() . '_process_transaction_result' ),
			'order_id'   => $order->get_id(),
			'Type'       => '{Type}',
			'InvoiceId'  => '{InvoiceId}',
			'Tip'        => '{Tip}',
			'Number'     => '{Number}',
			'GrandTotal' => '{GrandTotal}',
		);
	}


	/**
	 * Generates an invoice for the given order.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order
	 * @return array
	 */
	protected function generate_invoice( \WC_Order $order ) {

		/**
		 * Filters the order invoice data that is sent with the sideload request.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data the invoice data
		 * @param \WC_Order $order the order object
		 */
		$invoice = apply_filters( 'wc_' . $this->get_id() . '_order_invoice_data', array(
			'paymentTerms'    => 'DueOnReceipt',
			'currencyCode'    => $order->get_currency(),
			'number'          => $this->get_invoice_number( $order ),
			'payerEmail'      => $order->get_billing_email(),
			'itemList'        => array(
				'item' => array(
					array(
						'name'            => $this->get_order( $order )->description,
						'description'     => '',
						'quantity'        => '1',
						'unitPrice'       => $order->get_total( 'edit' ),
					),
				),
			),
		), $order );

		// don't allow this to be filtered
		$invoice['referrerCode'] = 'WooCommerce_POS_SL';

		return $this->remove_empty_params( $invoice );
	}


	/**
	 * Gets the invoice number for a given order.
	 *
	 * @since 1.0.1
	 *
	 * @param \WC_Order $order the order
	 * @return string
	 */
	protected function get_invoice_number( $order ) {

		$order_number = $order->get_order_number();

		// PayPal imposes a limit of 25 characters for the invoice number, so we
		// truncate it here and ensure that we truncate from the invoice prefix
		// so that we can include the full order number, that way if we do have to truncate
		// we can still reasonably ensure a unique invoice number so it doesn't fail
		return substr( $this->get_invoice_prefix(), 0, 25 - strlen( $order_number ) ) . $order_number;
	}


	/**
	 * Returns an API response object for the current response request
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::get_transaction_response()
	 *
	 * @since 1.0.0
	 *
	 * @param array $request_response_data the current request response data
	 * @return Sideloader_Response
	 * @throws Framework\SV_WC_Payment_Gateway_Exception
	 */
	protected function get_transaction_response( $request_response_data ) {

		if ( ! check_ajax_referer( 'wc_' . $this->get_id() . '_process_transaction_result', 'nonce', false ) ) {
			throw new Framework\SV_WC_Payment_Gateway_Exception( 'Invalid nonce' );
		}

		return new Sideloader_Response( $request_response_data );
	}


	/**
	 * Completes an order payment.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Hosted::complete_payment()
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order order object
	 * @param Framework\SV_WC_Payment_Gateway_API_Response|\Automattic\WooCommerce\PayPal_Here\Sideloader_Response $response response object
	 * @throws \Exception
	 */
	protected function complete_payment( \WC_Order $order, Framework\SV_WC_Payment_Gateway_API_Response $response ) {

		// reconcile differences in the order total - these can fall out of sync
		// when adding tips and taxes via the app, or applying a manual discount
		// inside the app that isn't present in the WC Order. Currently there is
		// no way to distinguish between these activities, so we can only make a
		// blanket adjustment and add to the WC order to stay in sync on totals.
		$difference = $this->get_total_difference( $order, $response );

		if ( $difference !== 0.0 ) {

			$this->add_total_adjustment_item( $order, $difference );
		}

		parent::complete_payment( $order, $response );

		// set custom paid status
		if ( null !== $this->default_order_status && '' !== $this->default_order_status ) {

			$order->set_status( $this->default_order_status );
			$order->save();
		}
	}


	/**
	 * Gets the difference in total between the order and the PayPal Here response.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param Sideloader_Response $response the response object
	 * @return float
	 */
	protected function get_total_difference( \WC_Order $order, Sideloader_Response $response ) {

		wc_get_price_decimals();

		$order_total    = (float) $order->get_total( 'edit' );
		$response_total = (float) $response->get_total();

		return $response_total - $order_total;
	}


	/**
	 * Adds an adjustment item to the order so that the totals will match between WC and PayPal Here.
	 *
	 * @since 1.0.0
	 *
	 * @param \WC_Order $order the order object
	 * @param float $adjustment_amount the amount of the adjustment to add
	 * @throws \Exception
	 */
	protected function add_total_adjustment_item( \WC_Order $order, $adjustment_amount ) {

		if ( 0.0 === (float) $adjustment_amount ) {
			return;
		}

		$fee = new \WC_Order_Item_Fee();

		$fee->set_props( array(
			'name'      => __( 'PayPal Here Adjustment', 'woocommerce-gateway-paypal-here' ),
			'tax_class' => 0,
			'total'     => $adjustment_amount,
			'total_tax' => 0,
			'taxes'     => array(
				'total' => 0,
			),
			'order_id'  => $order->get_id(),
		) );

		$fee_id = $fee->save();

		wc_add_order_item_meta( $fee_id, '_wc_paypal_here_adjustment_fee', '' );

		$order->add_item( $fee );
		$order->calculate_totals();
	}


	/**
	 * Returns an array of form fields specific for this method.
	 *
	 * To add environment-dependent fields, include the 'class' form field argument
	 * with 'environment-field production-field' where "production" matches a
	 * key from the environments member
	 *
	 * @see Framework\SV_WC_Payment_Gateway::get_method_form_fields()
	 *
	 * @since 1.0.0
	 *
	 * @return array of form fields
	 */
	protected function get_method_form_fields() {

		 return array(
			'paypal_email' => array(
				'title'       => __( 'PayPal Email', 'woocommerce-gateway-paypal-here' ),
				'description' => __( 'Please enter your PayPal email address; this is needed in order to take payment.', 'woocommerce-gateway-paypal-here' ),
				'desc_tip'    => esc_html__( 'The email address your PayPal merchant account is associated with.', 'woocommerce-gateway-paypal-here' ),
				'type'        => 'text',
				'class'       => 'environment-field production-field',
			),
			'invoice_prefix' => array(
				'title'       => __( 'Invoice Prefix', 'woocommerce-gateway-paypal-here' ),
				'description' => __( 'Please enter a prefix for your invoice numbers. This prefix will be truncated if the resulting order number exceeds 25 characters.', 'woocommerce-gateway-paypal-here' ),
				'desc_tip'    => esc_html__( 'If you use your PayPal account for multiple stores ensure this prefix is unique. PayPal will not allow orders with the same invoice number.', 'woocommerce-gateway-paypal-here' ),
				'type'        => 'text',
			),
			'default_order_status' => array(
				'title'       => __( 'Default Order Status', 'woocommerce-gateway-paypal-here' ),
				'description' => __( 'Please select the default status for successful PayPal Here transactions.', 'woocommerce-gateway-paypal-here' ),
				'type'        => 'select',
				'options'     => $this->get_paid_order_statuses(),
				'default'     => 'completed',
			),
		);
	}


	/**
	 * Gets the protocol used for sideload URL requests.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_sideload_protocol() {

		/**
		 * Filters the URL protocol to allow the sideload URLs to send to other apps that may implement the PayPal Here API.
		 *
		 * @since 1.0.0
		 *
		 * @param string $protocol the protocol
		 */
		return apply_filters( 'wc_' . $this->get_id() . '_sideload_url_protocol', self::PROTOCOL );
	}


	/**
	 * Gets the endpoint used for taking payment via the sideload API.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_sideload_endpoint() {

		$detected_android = $this->detect_android();
		$endpoint         = $detected_android ? self::ANDROID_ENDPOINT : self::ENDPOINT;

		/**
		 * Filters the endpoint used for the sideloader API.
		 *
		 * @since 1.0.0
		 *
		 * @param string $endpoint the endpoint for the sideloader API
		 * @param bool $detected_android whether an Android device has been detected or not
		 */
		return apply_filters( 'wc_' . $this->get_id() . '_sideload_url_endpoint', $endpoint, $detected_android );
	}


	/**
	 * Detects if the site is currently running on an Android device.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function detect_android() {

		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

		return stripos( $user_agent,'android' ) !== false;
	}


	/**
	 * Gets the invoice prefix.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_invoice_prefix() {

		return $this->invoice_prefix;
	}


	/**
	 * Returns an array of registered order statuses that are considered 'paid'.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_paid_order_statuses() {

		return array_combine( wc_get_is_paid_statuses(), array_map( 'wc_get_order_status_name', wc_get_is_paid_statuses() ) );
	}


	/**
	 * Recursively removes empty parameters from an array.
	 *
	 * In this case, an empty parameter is defined as either
	 * an empty string or an empty array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $array the array to remove empty params from
	 * @return array
	 */
	protected function remove_empty_params( $array ) {

		if ( ! is_array( $array ) ) {

			return $array;
		}

		foreach( $array as $key => $value ) {

			$array[ $key ] = $this->remove_empty_params( $value );

			if ( '' === $array[ $key ] || ( is_array( $array[ $key ] ) && empty( $array[ $key ] ) ) ) {

				unset( $array[ $key ] );
			}
		}

		return $array;
	}


	/**
	 * Logs a sideload request URI.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sideload_uri the base sideloader URI
	 * @param array $sideload_params the params being passed to the sideloader
	 */
	protected function log_sideload_url_request( $sideload_uri, $sideload_params ) {

		if ( isset( $sideload_params['invoice'] ) ) {

			$sideload_params['invoice'] = json_decode( base64_decode( $sideload_params['invoice'] ) );
		}

		$sideload_params = json_encode( $sideload_params, JSON_PRETTY_PRINT );

		$log_request = array(
			'method' => 'GET',
			'uri'    => $sideload_uri,
			'body'   => $sideload_params
		);

		$this->log_hosted_pay_page_request( $log_request );
	}


	/**
	 * Returns true if all debugging is disabled
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function debug_off() {
		return 'yes' !== $this->debug_log;
	}


	/**
	 * Returns true if debug logging is enabled
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function debug_log() {
		return 'yes' === $this->debug_log;
	}


	/**
	 * Returns false since there are no frontend checkout pages for this gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return false
	 */
	public function debug_checkout() {
		return false;
	}


	/**
	 * Returns the production environment ID, since PayPal Here only has a
	 * production environment.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_environment() {
		return self::ENVIRONMENT_PRODUCTION;
	}


	/**
	 * Returns false since there is no API for this gateway.
	 *
	 * @since 1.0.0
	 *
	 * @return false
	 */
	public function get_api() {
		return false;
	}


}
