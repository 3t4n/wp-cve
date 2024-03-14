<?php

class Quadpay_WC_Gateway extends WC_Payment_Gateway {

	/**
	 * The reference to the singleton instance of this class
	 *
	 * @var $_instance WC_Gateway_QuadPay
	 */
	private static $_instance = NULL;

	/**
	 * @var string
	 */
	private $client_id;

	/**
	 * @var string
	 */
	private $client_secret;

	/**
	 * @var string
	 */
	private $mode;

	/**
	 * @var string
	 */
	private $minimum_amount;

	/**
	 * @var string
	 */
	private $maximum_amount;

	/**
	 * @var Quadpay_WC_Settings
	 */
	private $quadpay_settings;

	/**
	 * Main WC_Gateway_QuadPay Instance
	 *
	 * Used for WP-Cron jobs when
	 *
	 * @return WC_Gateway_QuadPay Main instance
	 */
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		$this->id = 'quadpay';

		$this->method_title = __( 'Zip - Pay over time', 'woo_quadpay' );
		$this->method_description = __( 'Use Zip as a credit or debit card processor for WooCommerce.', 'woo_quadpay' );
		$this->title = __( 'Zip - Pay over time', 'woo_quadpay');
		$this->description = __('Pay over time.','woo_quadpay');
		$this->supports = [ 'products', 'refunds' ];
		$this->icon = QUADPAY_WC_PLUGIN_URL . 'assets/images/zip-logo-color.svg';

		$this->quadpay_settings = Quadpay_WC_Settings::instance();

		$this->init_options();

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		if ( $this->mode !== 'production' && !is_admin() ) {
			$this->title .= ' (TEST MODE)';
		}

		$this->init();
	}

	public function init()
	{
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );

		add_action( 'woocommerce_thankyou', [ $this, 'payment_callback' ] );

		add_action( 'woocommerce_update_options_checkout', [ $this, 'save_options_page' ] );

		add_action( 'woocommerce_order_status_changed', [ $this, 'process_capture' ], null, 4 );
		add_action( 'woocommerce_order_status_changed', [ $this, 'process_void' ], null, 4 );
	}

	/**
	 * Collect options
	 */
	private function init_options() {
		$this->client_id = $this->get_option( 'client_id' );
		$this->client_secret = $this->get_option( 'client_secret' );
		$this->mode = $this->get_option( 'testmode' );
		$this->minimum_amount = $this->get_option( 'quadpay-amount-minimum', 35 );
		$this->maximum_amount = $this->get_option( 'quadpay-maximum-amount', 1500 );
	}

	/**
	 * @inheritDoc
	 */
	public function is_available() {

		if ( 'yes' !== $this->enabled ) {
			return false;
		}

		if ( ! ( $this->client_id && $this->client_secret ) ) {
			return false;
		}

		if ( is_admin() || !WC()->cart ) {
			return true;
		}

		$total = $this->get_order_total();
		if ( $total < $this->minimum_amount || $total > $this->maximum_amount ) {
			return false;
		}

		$billing_country = '';
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '<' ) ) {
			global $woocommerce;
			$billing_country = $woocommerce->customer->country;
		} else if ( null !== WC()->customer && null !== WC()->customer->get_billing_country() ) {
			// make sure to call the member function only if not null
			// @since 1.3.11
			$billing_country = WC()->customer->get_billing_country();
		}

		if ( ! in_array( $billing_country, Quadpay_WC_Settings::ALLOWED_COUNTRIES ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields =  Quadpay_WC_Settings::instance()->get_form_fields();
	}

	/**
	 * Admin Panel Options
	 */
	public function admin_options() {
		?>
		<h2><?php _e( 'Zip Gateway', 'woo_quadpay' ); ?></h2>
		<?php
		$login = ( $this->get_quadpay_authorization_code() !== false );
		?>
		<p style="color: <?php echo $login ? 'green' : 'red' ?>">
			<?php echo $login ?
				__( 'Validation of Zip login credentials passed successful.', 'woo_quadpay' ) :
				__( 'Zip login failed. Please check your Client ID and Client Secret.', 'woo_quadpay' );
			?>
		</p>

		<h3 class="wc-settings-sub-title " id="woocommerce_quadpay_payment_limits"><?php _e( 'Payment Limits', 'woo_quadpay ' )?></h3>
		<p><?php _e( 'The possible minimum and maximum payment limit for the Zip payment.', 'woo_quadpay' )?></p>
		<table class="form-table">
			<tbody><tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woocommerce_quadpay_min_amount"><?php _e( 'Minimum Amount', 'woo_quadpay' ) ?></label>
				</th>
				<td class="forminp">
					<?php echo $this->minimum_amount ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woocommerce_quadpay_max_amount"><?php _e( 'Maximum Amount', 'woo_quadpay' ) ?></label>
				</th>
				<td class="forminp">
					<?php echo $this->maximum_amount ?>
				</td>
			</tr>
			<?php if ($this->get_option('defer_funds_capture', false)): ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="woocommerce_quadpay_max_amount"><?php _e( 'Defer Funds Capture', 'woo_quadpay' ) ?></label>
				</th>
				<td class="forminp"><?php _e( 'Yes', 'woo_quadpay' ) ?></td>
			</tr>
			<?php endif; ?>
			</tbody>
		</table>

		<table class="form-table">
			<?php
			// Generate the HTML For the settings form.
			$this->generate_settings_html( $this->form_fields, true);
			?>
		</table><!--/.form-table-->
		<?php
	}

	/**
	 * Display payment options on the checkout page
	 */
	public function payment_fields() {

		if ( $this->quadpay_settings->get_option_bool( 'enable_payment_widget' ) ) {
			echo Quadpay_WC_Widget::instance()->get_payment_widget();
		} else {
			echo '<p>' . $this->description . '</p>';

			$cc_icons_base = QUADPAY_WC_PLUGIN_URL . 'assets/images/cc/';
			$cc_types = [
				'visa',
				'mastercard',
				'amex',
				'discover'
			];

			echo '<p>';
			echo '<span>' . __('Credit and debit cards accepted:', 'woo_quadpay') . '</span>';
			foreach ($cc_types as $cc_type) {
				echo '<img class="qp_cc_type" src="' . $cc_icons_base . $cc_type . '.svg" style="height:23px; margin-left: 5px;">';
			}
			echo '</p>';
		}
	}

	/**
	 * Request an order token from QuadPay
	 *
	 * @return string|false
	 * @deprecated
	 */
	public function get_quadpay_authorization_code() {
		return Quadpay_WC_Api::instance()->get_auth_token() ?: false;
	}

	/**
	 * Save options page in admin, update payment limits
	 */
	public function save_options_page() {
		self::log( __METHOD__ );
		wp_cache_flush();
		// reload settings and options
		$this->init_settings();
		$this->init_options();
		// update configuration from api
		$this->update_payment_limits();
	}

	/**
	 * Update settings from configuration api (min, max and dfc)
	 *
	 * @return bool
	 */
	public function update_payment_limits() {

		if ( !$this->client_id || !$this->client_secret ) {
			return false;
		}

		self::log( __METHOD__ );

		$response = Quadpay_WC_Api::instance()->configuration();

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$this->quadpay_settings->update_options([
			'quadpay-amount-minimum' => $response->minimumAmount,
			'quadpay-maximum-amount' => $response->maximumAmount,
			'defer_funds_capture' => $response->deferFundsCapture
		]);

		return true;
	}

	/**
	 * Process the payment and return the result
	 * - redirects the customer to the pay page
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		//Process here
		$order_items = $order->get_items();
		$items = array();

		if ( count($order_items ) ) {

			foreach ( $order_items as $item ) {

				$product = wc_get_product( $item['variation_id'] ?: $item['product_id'] );

				$items[] = array(
					'name'     => $item['name'],
					'sku'      => $product->get_sku(),
					'quantity' => $item['qty'],
					'price'    => number_format( ( $item['line_subtotal'] / $item['qty'] ), 2, '.', '' ),
				);

			}
		}

		//calculate total shipping amount
		if ( method_exists( $order, 'get_shipping_total') ) {
			//WC 3.0
			$shipping_total = $order->get_shipping_total();
		} else {
			//WC 2.6.x
			$shipping_total = $order->get_total_shipping();
		}

		// get MFPP if used
		$mfpp_total = 0;
		foreach ( $order->get_fees() as $fee ) {
			if ( $fee->get_name() === __( Quadpay_WC_Mfpp::NAME, 'woo_quadpay' ) ) {
				$mfpp_total = $fee->get_total();
				break;
			}
		}

		$body = array(
			'amount'            => number_format( $order->get_total(), 2, '.', '' ),
			'consumer'          => array(
				'phoneNumber' => $this->get_order_prop( $order, 'billing_phone' ),
				'givenNames'  => $this->get_order_prop( $order, 'billing_first_name' ),
				'surname'     => $this->get_order_prop( $order, 'billing_last_name' ),
				'email'       => $this->get_order_prop( $order, 'billing_email' ),
			),
			'billing'           => array(
				'addressLine1' => $this->get_order_prop( $order, 'billing_address_1' ),
				'addressLine2' => $this->get_order_prop( $order, 'billing_address_2' ),
				'city'         => $this->get_order_prop( $order, 'billing_city' ),
				'state'        => $this->get_order_prop( $order, 'billing_state' ),
				'postcode'     => $this->get_order_prop( $order, 'billing_postcode' ),
			),
			'shipping'          => array(
				'addressLine1' => $this->get_order_prop( $order, 'shipping_address_1' ),
				'addressLine2' => $this->get_order_prop( $order, 'shipping_address_2' ),
				'city'         => $this->get_order_prop( $order, 'shipping_city' ),
				'state'        => $this->get_order_prop( $order, 'shipping_state' ),
				'postcode'     => $this->get_order_prop( $order, 'shipping_postcode' ),
			),
			'description'       => "Order #$order_id",
			'items'             => $items,
			'merchant'          => array(
				'redirectConfirmUrl' => $this->get_return_url( $order ) . '&status=confirmed',
				'redirectCancelUrl'  => $this->get_return_url( $order ) . '&status=cancelled',
			),
			'merchantReference' => $order_id,
			'taxAmount'         => $order->get_total_tax(),
			'shippingAmount'    => $shipping_total,
			'merchantFeeForPaymentPlan' => $mfpp_total,
			'metadata' => array(
				'platform' => 'WooCommerce'
			)
		);

		self::log( 'POST Order request');
		$response = Quadpay_WC_Api::instance()->order( $body );

		// Couldn't generate token
		if ( is_wp_error($response) ) {

			$order->add_order_note(
				__('Unable to generate the order token. Payment could not proceed.', 'woo_quadpay' )
			);

			wc_add_notice(
				__('Sorry, there was a problem preparing your payment. Please try again.', 'woo_quadpay'),
				'error'
			);

			return [];
		}

		// Order token successful, save it so we can confirm it later
		update_post_meta( $order_id, '_quadpay_order_token', $response->token );
		update_post_meta( $order_id, '_quadpay_order_id', $response->orderId );

		return array(
			'result' 	=> 'success',
			'redirect'	=> $response->redirectUrl
		);
	}

	/**
	 * Resolves Zip order id from order metadata
	 *
	 * @param $order_id
	 * @return string|false
	 */
	public function get_quadpay_order_id( $order_id ) {

		$quadpay_order_id = get_post_meta( $order_id, '_quadpay_order_id', true );

		if ( ! empty( $quadpay_order_id ) ) {
			return $quadpay_order_id;
		}

		$order_token  = get_post_meta( $order_id, '_quadpay_order_token', true );

		if ( empty( $order_token ) ) {
			return false;
		}

		update_post_meta($order_id, '_quadpay_order_id', $order_token );

		return $order_token;
	}

	/**
	 * Thank You page callback
	 *
	 * @param int $order_id
	 */
	public function payment_callback( $order_id ) {

		$order = wc_get_order( $order_id );

		if ( 'quadpay' !== $order->get_payment_method() ) {
			return;
		}

		//save the order id
		$quadpay_order_id = $this->get_quadpay_order_id( $order_id );

		if ( $quadpay_order_id === false ) {
			$order->add_order_note(sprintf(__('Failure to process the Zip payment.', 'woo_quadpay')));
			return;
		}

		// check response and not query
		$this->sync_order_status( $order );
	}

	/**
	 * Can the order be refunded
	 *
	 * @param  WC_Order $order
	 * @return bool
	 */
	public function can_refund_order( $order ) {
		//return $order && $order->get_transaction_id();
		return $order && in_array( $order->get_status(), wc_get_is_paid_statuses() );
	}

	/**
	 * Process a refund
	 *
	 * @param int $order_id
	 * @param float $amount
	 * @param string $reason
	 *
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {

		$quadpay_order_id = $this->get_quadpay_order_id( $order_id );
		$order = wc_get_order( $order_id );

		if ( empty( $quadpay_order_id ) ) {
			$order->add_order_note( sprintf( __( 'There was an error submitting the refund to Zip.', 'woo_quadpay' ) ) );
			return false;
		}

		$merchant_refund_reference = 'Order #' . $order_id . '-' . wp_generate_password( 8, false, false );

		$response = Quadpay_WC_Api::instance()->refund( $quadpay_order_id, $amount, $merchant_refund_reference );

		if ( is_wp_error($response) ) {
			$order->add_order_note( sprintf( __( 'There was an error submitting the refund to Zip.', 'woo_quadpay' ) ) );
			return false;
		}

		$order->add_order_note(sprintf(
				__( 'Refund of %s successfully sent to Zip.', 'woo_quadpay' ),
				wc_price( $amount, array( 'currency' => $order->get_currency() ) )
		) );
		return true;
	}

	/**
	 * Capture order on Zip side, DFC
	 *
	 * @param int $order_id
	 * @param string $from
	 * @param string $to
	 * @return bool
	 */
	public function process_capture( $order_id, $from, $to ) {

		// no need for manual capture
		if ( !$this->get_option('defer_funds_capture') ) {
			return false;
		}

		if ( ! ( in_array( $from, [ 'pending', 'on-hold' ] ) && in_array( $to, wc_get_is_paid_statuses() ) ) ) {
			return false;
		}

		$quadpay_order_id = $this->get_quadpay_order_id( $order_id );
		if ( empty( $quadpay_order_id ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );
		$amount = $order->get_total() - $order->get_total_refunded();

		if ($amount < 0.01) {
			return false;
		}

		$merchant_reference = 'Order #' . $order_id . '-' . wp_generate_password( 8, false, false );

		$response = Quadpay_WC_Api::instance()->capture( $quadpay_order_id, $amount, $merchant_reference );

		if ( is_wp_error($response) ) {
			$order->add_order_note(
					sprintf( __( 'There was an error submitting the capture to Zip.', 'woo_quadpay' ) ) .
					' ' .
					$response->get_error_message()
			);
			return false;
		}

		$order->payment_complete( $quadpay_order_id );
		$order->add_order_note(sprintf(
				__( 'Capture of %s successfully sent to Zip.', 'woo_quadpay' ),
				wc_price( $amount, array( 'currency' => $order->get_currency() ) )
		) );

		return true;
	}

	/**
	 * Void order on Zip side, DFC
	 *
	 * @param $order_id
	 * @param string $from
	 * @param string $to
	 * @return bool
	 */
	public function process_void( $order_id, $from, $to ) {

		if ( !$this->get_option('defer_funds_capture') ) {
			return false;
		}

		if ( ! ( in_array( $from, [ 'pending', 'on-hold' ] ) && in_array( $to, [ 'cancelled', 'failed' ] ) ) ) {
			return false;
		}

		$quadpay_order_id = $this->get_quadpay_order_id( $order_id );
		if ( empty( $quadpay_order_id ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );
		$amount = $order->get_total() - $order->get_total_refunded();

		if ($amount < 0.01) {
			return false;
		}

		$merchant_reference = 'Order #' . $order_id . '-' . wp_generate_password( 8, false, false );

		/** @var WP_Error $response */
		$response = Quadpay_WC_Api::instance()->void( $quadpay_order_id, $amount, $merchant_reference );

		if ( is_wp_error($response) ) {
			$order->add_order_note(
					sprintf( __( 'There was an error submitting the void to Zip.', 'woo_quadpay' ) ) .
					' ' .
					$response->get_error_message()
			);
			return false;
		}

		$order->add_order_note(sprintf(
				__( 'Void of %s successfully sent to Zip.', 'woo_quadpay' ),
				wc_price( $amount, array( 'currency' => $order->get_currency() ) )
		) );
		return true;
	}

	/**
	 * Logging method
	 *
	 * @param string $message
	 * @param string|null $source
	 */
	public static function log( $message, $source = null ) {
		Quadpay_WC_Logger::log( $message, $source );
	}

	/**
	 * Check the order status of all pending orders that didn't return to the thank you page or marked as Pending by Zip
	 *
	 * @note: old code was also checking on-hold orders, but we don't have any (only DFC)
	 */
	public function check_pending_orders() {

		// Default value 24h
		$order_check_time = $this->get_option( 'order_check_time', 'DAY_IN_SECONDS');

		// Get PENDING orders that may have been abandoned, or browser window closed after approved
		$args = array(
			'status'         => 'pending',
			'type'           => 'shop_order',
			'date_created'   => '>' . ( time() - constant( $order_check_time ) ),
			'limit'          => -1,
			'payment_method' => 'quadpay',
		);

		$pending_orders = wc_get_orders( $args );

		if ( empty( $pending_orders ) ) {
			return;
		}

		foreach ( $pending_orders as $order ) {
			$this->sync_order_status($order);
		}
	}

	/**
	 * Checks order status on Zip and updates order accordingly
	 *
	 * @param WC_Order $order
	 * @param bool $forceStatus
	 * @return bool
	 */
	public function sync_order_status( $order, $forceStatus = false ) {

		$payment_method = $order->get_payment_method();

		if ( 'quadpay' !== $payment_method ) {
			return false;
		}

		$order_id = $order->get_id();
		$quadpay_order_id = $this->get_quadpay_order_id( $order_id );

		if ( ! $quadpay_order_id ) {
			return false;
		}

		self::log( 'Order ID ' . $order_id . ', Zip Order ID ' . $quadpay_order_id, __METHOD__ );
		$response = Quadpay_WC_Api::instance()->get_order( $quadpay_order_id );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$order->add_order_note(
			sprintf(
				__('Zip payment %s. Zip Order ID: %s', 'woo_quadpay'),
				strtolower($response->orderStatus),
				$response->orderId
			)
		);

		// Check status of order
		switch ($response->orderStatus) {
			case 'Approved':
				if ( $forceStatus || $order->get_status() === 'pending' ) {

					if ( $this->get_option('defer_funds_capture') ) {
						$order->update_status('on-hold');
					} else {
						$order->payment_complete($quadpay_order_id);
					}

				}

				break;

			case 'Created':
				if ( $forceStatus && $order->get_status() !== 'pending' ) {
					$order->update_status('pending');
				}

				break;

			case 'Abandoned':
			case 'Declined':
				if ( $forceStatus || $order->get_status() === 'pending' ) {
					$order->update_status('cancelled');
				}

				break;

			default:
				if ( $forceStatus ) {
					$order->update_status('failed');
				}

				break;
		}

		return true;
	}

	/**
	 * Gets an order property.
	 *
	 * This method exists for WC 3.0+ compatibility.
	 *
	 * @param WC_Order $order
	 * @param string $prop
	 * @return mixed
	 */
	private function get_order_prop( WC_Order $order, $prop ) {

		$wc_version = defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;

		if ( $wc_version && version_compare( $wc_version, '3.0', '>=' ) && is_callable( array( $order, "get_{$prop}" ) ) ) {
			$value = $order->{"get_{$prop}"}();
		} else {
			$value = $order->$prop;
		}

		return $value;
	}
}

/**
 * Class WC_Gateway_QuadPay
 *
 * Backward compatibility
 */
class WC_Gateway_QuadPay extends Quadpay_WC_Gateway {}
