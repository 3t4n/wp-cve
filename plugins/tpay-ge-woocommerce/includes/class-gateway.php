<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     TBC Checkout for WooCommerce
 * @author      Plug and Pay Ltd. http://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       1.0.0
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace PlugandPay\TBC_Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC (Checkout) gateway class.
 */
class Gateway extends \WC_Payment_Gateway {

	/**
	 * The current version of the plugin.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $version;

	/**
	 * Supported languages.
	 * locale => api lang.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $supported_languages = [
		'ka_GE' => 'KA',
		'en_US' => 'EN',
		'ru_RU' => 'RU',
	];

	/**
	 * Supported currencies.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	public $supported_currencies = [ 'GEL', 'USD', 'EUR' ];

	/**
	 * Whether or not logging is enabled.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance.
	 *
	 * @since 1.0.0
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param string $software_version Current software version of this plugin.
	 */
	public function __construct( $software_version ) {
		$this->version            = $software_version;
		$this->id                 = 'tpay_gateway';
		$this->has_fields         = false;
		$this->method_title       = __( 'TBC E-commerce', 'tbc-checkout' );
		$this->method_description = __( 'Add a TBC E-commerce button to your website and start selling with TBC E-commerce.', 'tbc-checkout' );
		$this->supports           = [
			'products',
			'refunds',
		];

		$this->init();

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'save_client_accounts' ] );
		add_action( 'woocommerce_api_' . $this->route_return, [ $this, 'route_return' ] );
		add_action( 'woocommerce_api_' . $this->route_callback, [ $this, 'route_callback' ] );
	}

	/**
	 * Initialise gateway settings.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->form_fields = include 'settings/gateway.php';
		$this->init_settings();

		// User set variables.
		$this->title             = $this->get_option( 'title' );
		$this->description       = $this->get_option( 'description' );
		$this->order_button_text = $this->get_option( 'order_button_text' );
		$this->debug             = 'yes' === $this->get_option( 'debug', 'no' );
		self::$log_enabled       = $this->debug;
		$this->payment_action    = $this->get_option( 'payment_action' );
		$this->skip_info_message = 'yes' === $this->get_option( 'skip_info_message', 'no' );
		$this->client_accounts   = get_option(
			sprintf( 'woocommerce_%s_client_accounts', $this->id ),
			[
				[
					'currency'      => $this->get_option( 'currency' ) ?: 'GEL',
					'client_id'     => $this->get_option( 'client_id' ),
					'client_secret' => $this->get_option( 'client_secret' ),
				],
			]
		);

		$this->payment_methods = [];

		$method_to_id = [
			'card_payments'    => '5',
			'qr_payments'      => '4',
			'ertguli_payments' => '6',
			'apple_payments'   => '9',
			'ib_payments'      => '7',
		];

		foreach ( $method_to_id as $method => $id ) {
			$option = $this->get_option( $method );

			if ( 'yes' === $option ) {
				array_push( $this->payment_methods, $id );
			}
		}

		// Routes.
		$this->route_return   = 'tbc-checkout/return';
		$this->route_callback = 'tbc-checkout/callback';

		// API.
		$this->api_url = 'https://api.tbcbank.ge/v1/tpay/';
	}

	/**
	 * Logging method.
	 *
	 * @since 1.0.0
	 * @param string $message Log message.
	 * @param string $level Optional. Default 'info'. Possible values:
	 *                      emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, [ 'source' => 'tpay_gateway' ] );
		}
	}

	/**
	 * Display notices in admin dashboard.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_notices() {
		if ( ! $this->has_required_options() ) {
			/* translators: Gateway settings url */
			echo '<div class="error"><p>' . wp_kses_data( sprintf( __( 'TBC Checkout for WooCommerce: Please fill out required options <a href="%s">here</a>.', 'tbc-checkout' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $this->id ) ) ) . '</p></div>';
		}
	}

	/**
	 * Output the gateway settings screen.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_options() {
		echo '<h2>' . esc_html( $this->get_method_title() );
		wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
		echo '</h2>';
		echo wp_kses_post( wpautop( $this->get_method_description() ) );
		\WC_Settings_API::admin_options();
		?>
		<div style="border: 1px dotted #ccc; padding-left: 15px;">
			<p><?php esc_html_e( 'These pages are automatically created by plugin!', 'tbc-checkout' ); ?></p>
			<ul>
				<li><?php echo esc_url( sprintf( '%s/wc-api/%s', get_bloginfo( 'url' ), $this->route_callback ) ); ?></li>
			</ul>
			<p style="color:orange;"><?php echo is_ssl() ? '' : esc_html__( 'https (SSL) is recommended! Please install a certificate.', 'tbc-checkout' ); ?></p>
			<?php if ( self::$log_enabled && $this->client_accounts ) { ?>
				<?php foreach ( $this->client_accounts as $account ) { ?>
					<p style="color:green;">
						<?php $transient_key = '_transient_timeout_' . $this->id . '_access_token_' . $account['currency']; ?>
						<?php echo $this->get_access_token( $account['currency'] ) ? esc_html( __( 'Access token retrieved. Expires: ', 'tbc-checkout' ) . date_i18n( get_option( 'date_format' ), get_option( $transient_key ) ) . ', For currency: ' . $account['currency'] ) : ''; ?>
					</p>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Is this gateway available?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_available() {
		return parent::is_available() &&
			$this->has_required_options() &&
			$this->can_process_currency( get_woocommerce_currency() );
	}

	/**
	 * Are all required options filled out?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function has_required_options() {
		return $this->validate_client_accounts( $this->client_accounts );
	}

	/**
	 * Check if currency codes are valid and that ids and secrets are not empty.
	 *
	 * @since 2.0.0
	 * @param array $accounts Client accounts.
	 * @return bool
	 */
	public function validate_client_accounts( $accounts ) {
		if ( is_array( $accounts ) && ! empty( $accounts ) ) {
			foreach ( $accounts as $account ) {
				if ( ! in_array( $account['currency'], $this->supported_currencies, true ) || empty( $account['client_id'] ) || empty( $account['client_secret'] ) ) {
					return false;
				}
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * See if currency can be processed by gateway.
	 *
	 * @since 2.0.0
	 * @param string $currency Alphabetic currency code.
	 * @return bool
	 */
	public function can_process_currency( $currency ) {
		$currency_key = array_search( $currency, array_column( $this->client_accounts, 'currency' ), true );
		return in_array( $currency, $this->supported_currencies, true ) && false !== $currency_key && null !== $currency_key;
	}

	/**
	 * API request.
	 *
	 * @since 1.0.0
	 * @param array  $params Query string parameters.
	 * @param string $uri URI.
	 * @param array  $headers HTTP Headers.
	 * @param string $type Request type.
	 * @return array|false
	 */
	public function api_request( $params, $uri, $headers = [], $type = 'POST' ) {

		$this->log( 'Making an API request...', 'info' );

		$response = wp_remote_request(
			$this->api_url . $uri,
			[
				'method'  => $type,
				'body'    => $params,
				'headers' => array_merge(
					$headers,
					[ 'apikey' => 'lJVjluJXcDL8iAN86NNeceOQW3kolwnF' ]
				),
			]
		);

		if ( is_wp_error( $response ) ) {
			$this->log( $response->get_error_message(), 'error' );
			return false;
		}

		if ( wp_remote_retrieve_response_code( $response ) === 401 ) {
			$this->log( 'API returned 401 code. Delete transient.', 'error' );
			delete_transient( $this->id . '_access_token' );
			return false;
		}

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			$this->log( sprintf( 'API returned %s code. halt!', wp_remote_retrieve_response_code( $response ) ), 'error' );
			return false;
		}

		$body = wp_remote_retrieve_body( $response );

		return json_decode( $body, true );
	}

	/**
	 * API get access token.
	 *
	 * @since 1.0.0
	 * @param string $client_id Client id.
	 * @param string $client_secret Client secret.
	 * @return array|false
	 */
	public function get_access_token_from_api( $client_id, $client_secret ) {

		return $this->api_request(
			[
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
			],
			'access-token'
		);

	}

	/**
	 * Get access token from transient or set it.
	 *
	 * @since 1.0.0
	 * @param string $currency Alphabetic currency code.
	 * @return string|false
	 */
	public function get_access_token( $currency ) {
		$transient_key = $this->id . '_access_token_' . $currency;
		$access_token  = get_transient( $transient_key );

		if ( false === $access_token ) {
			$account = $this->get_client_account( $currency );

			if ( ! $account ) {
				$this->log( sprintf( 'Account for %s not found. Could not get access token.', $currency ), 'error' );
				return false;
			}

			$response = $this->get_access_token_from_api( $account['client_id'], $account['client_secret'] );
			$this->log( sprintf( 'Get access token from API: %s', wp_json_encode( $response, JSON_PRETTY_PRINT ) ), 'info' );

			if ( $response && isset( $response['access_token'], $response['expires_in'] ) ) {
				set_transient( $transient_key, $response['access_token'], $response['expires_in'] );
				$access_token = $response['access_token'];
			} else {
				$this->log( 'API did not return an access_token.', 'critical' );
			}
		}

		return $access_token;
	}

	/**
	 * Get account for currency.
	 *
	 * @since 2.0.0
	 * @param string $currency Alphabetic currency code.
	 * @return array|false
	 */
	public function get_client_account( $currency ) {
		$currency_key = array_search( $currency, array_column( $this->client_accounts, 'currency' ), true );
		$account      = false;

		if ( false !== $currency_key && null !== $currency_key ) {
			$account = $this->client_accounts[ $currency_key ] ?? false;
		}

		return $account;
	}

	/**
	 * API register payment attempt.
	 *
	 * @since 1.0.0
	 * @param array $params Form params.
	 * @return arary|false
	 */
	public function register_payment_with_api( $params ) {

		return $this->api_request(
			wp_json_encode( $params ),
			'payments',
			[
				'Authorization' => 'Bearer ' . $this->get_access_token( $params['amount']['currency'] ),
				'Content-Type'  => 'application/json',
			]
		);

	}

	/**
	 * Process a refund if supported.
	 *
	 * @since 2.0.0
	 * @param  int    $order_id Order ID.
	 * @param  float  $amount Refund amount.
	 * @param  string $reason Refund reason.
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order->get_transaction_id() ) {
			$this->log( 'Refund not possible, transaction id missing.', 'error' );
			return new \WP_Error( 'error', __( 'Refund failed.', 'tbc-checkout' ) );
		}

		$this->log(
			sprintf(
				'Start refund, Order id: %s - amount: %s - reason: %s',
				$order->get_id(),
				$amount,
				$reason ?: 'none given'
			),
			'info'
		);

		$response = $this->api_request(
			wp_json_encode( [ 'amount' => $amount ] ),
			'payments/' . $order->get_transaction_id() . '/cancel',
			[
				'Authorization' => 'Bearer ' . $this->get_access_token( $order->get_currency() ),
				'Content-Type'  => 'application/json',
			]
		);

		if ( false === $response ) {
			return new \WP_Error( 'error', __( 'Refund failed.', 'tbc-checkout' ) );
		}

		$this->log( 'Success ~ refund done.', 'info' );
		return true;
	}

	/**
	 * Process the payment and redirect client.
	 *
	 * @since 1.0.0
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		$params = [
			'amount'          => [
				'currency' => $order->get_currency(),
				'total'    => $order->get_total(),
				'subTotal' => $order->get_subtotal(),
				'tax'      => $order->get_total_tax(),
				'shipping' => $order->get_shipping_total(),
			],
			'returnurl'       => esc_url( sprintf( '%s/wc-api/%s?order_id=%d', get_bloginfo( 'url' ), $this->route_return, $order_id ) ),
			'useripaddress'   => $order->get_customer_ip_address(),
			'extra'           => sprintf( 'Order id: %s', $order_id ),
			'language'        => $this->locale_to_lang( get_locale() ),
			'methods'         => $this->payment_methods,
			'SkipInfoMessage' => $this->skip_info_message,
			'cmsInfo'         => sprintf( 'WooCommerce PP %s %s', $this->id, $this->version ),
			'preAuth'         => 'authorize' === $this->payment_action,
			'callbackUrl'     => esc_url( sprintf( '%s/wc-api/%s', get_bloginfo( 'url' ), $this->route_callback ) ),
		];

		$this->log( sprintf( 'Params to send: %s, order_id: %d', wp_json_encode( $params, JSON_PRETTY_PRINT ), $order_id ), 'info' );

		$response = $this->register_payment_with_api( $params );

		$this->log( sprintf( 'Response on register_payment_with_api: %s, order_id: %d', wp_json_encode( $response, JSON_PRETTY_PRINT ), $order_id ), 'info' );

		if ( ! $response || ! isset( $response['payId'], $response['links'], $response['status'] ) || 'Created' !== $response['status'] ) {
			$this->log( sprintf( 'No valid response from TBC Checkout, order_id: %d', $order_id ), 'error' );
			wc_add_notice( __( 'TBC Checkout not responding, please try again later.', 'tbc-checkout' ), 'error' );
			return;
		}

		WC()->queue()->schedule_single(
			time() + MINUTE_IN_SECONDS * 2,
			'run_check_order_status_in_background',
			[
				'order_id'    => $order->get_id(),
				'retry_index' => 0,
			]
		);

		update_post_meta( $order->get_id(), '_transaction_id', $response['payId'] );

		$redirect_to = $this->find_redirect_url( $response['links'] );

		$this->log( sprintf( 'Order id: %d, redirecting user to TBC Checkout gateway: %s', $order_id, $redirect_to ), 'notice' );

		return [
			'result'   => 'success',
			'redirect' => $redirect_to,
		];
	}

	/**
	 * Search API reply for redirect link.
	 *
	 * @since 1.0.0
	 * @param array $array Heystack array.
	 * @return string
	 */
	public function find_redirect_url( $array ) {
		$key = array_search( 'REDIRECT', array_column( $array, 'method' ), true );
		return $array[ $key ]['uri'];
	}

	/**
	 * API check transaction status.
	 *
	 * @since 1.0.0
	 * @param string $trans_id Transaction id.
	 * @param string $currency Alphabetic currency code.
	 * @return array|false
	 */
	public function get_transaction_status_from_api( $trans_id, $currency ) {

		return $this->api_request(
			[],
			'payments/' . $trans_id,
			[
				'Authorization' => 'Bearer ' . $this->get_access_token( $currency ),
				'Content-Type'  => 'application/json',
			],
			'GET'
		);

	}

	/**
	 * Get order id by transaction id.
	 *
	 * @since 2.0.0
	 * @param string $trans_id Transaction id.
	 * @return int|null
	 */
	public function get_order_id_by_transaction_id( $trans_id ) {
		global $wpdb;

		$meta = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT * FROM $wpdb->postmeta
				 WHERE meta_key = '_transaction_id'
				   AND meta_value = %s
				 LIMIT 1
				",
				$trans_id
			)
		);

		if ( ! empty( $meta ) && is_array( $meta ) && isset( $meta[0] ) ) {
			$meta = $meta[0];
		}

		if ( is_object( $meta ) ) {
			return $meta->post_id;
		}

		return null;
	}

	/**
	 * Callback url.
	 *
	 * @since 2.0.0
	 */
	public function route_callback() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ( ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		$raw_post_data = $wp_filesystem->get_contents( 'php://input' );
		$post_data     = json_decode( $raw_post_data, true );

		$this->log( sprintf( 'Incoming callback, raw data posted: %s', $raw_post_data ), 'info' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		$trans_id = $post_data['PaymentId']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$order_id = $this->get_order_id_by_transaction_id( $trans_id );

		if ( ! $order_id ) {
			$this->log( sprintf( 'Cannot find order id by transaction id (PaymentId) %s.', $trans_id ), 'error' );
			status_header( 404 );
			exit;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			$this->log( sprintf( 'Cannot find order by order id %s.', $order_id ), 'error' );
			status_header( 404 );
			exit;
		}

		$response = $this->get_transaction_status_from_api( $trans_id, $order->get_currency() );

		$this->log( sprintf( 'TBC Checkout reply on status check: %s, order_id: %d', wp_json_encode( $response, JSON_PRETTY_PRINT ), $order_id ), 'info' );

		if ( $response && isset( $response['status'] ) ) {

			switch ( $response['status'] ) {

				case 'WaitingConfirm':
				case 'Succeeded':
					$this->payment_complete( $order, $trans_id );
					$this->log( 'TBC Checkout: payment successful.', 'notice' );
					exit;

				case 'Failed':
					$this->payment_failed( $order, $trans_id );
					$this->log( 'TBC Checkout: payment failed.', 'notice' );
					exit;
			}

			$this->log( 'API did not return status Succeeded or Failed.', 'error' );
		} else {
			$this->log( 'API did not return anything.', 'error' );
		}

		exit;
	}

	/**
	 * Redirect back from TBC Checkout.
	 *
	 * @since 1.0.0
	 */
	public function route_return() {
		$order_id = isset( $_GET['order_id'] ) ? (int) $_GET['order_id'] : false; // phpcs:ignore
		$order    = wc_get_order( $order_id );
		if ( $order ) {

			$trans_id = $order->get_transaction_id();
			$response = $this->get_transaction_status_from_api( $trans_id, $order->get_currency() );

			$this->log( sprintf( 'TBC Checkout reply on status check: %s, order_id: %d', wp_json_encode( $response, JSON_PRETTY_PRINT ), $order_id ), 'info' );

			if ( $response && isset( $response['status'] ) ) {

				switch ( $response['status'] ) {

					case 'WaitingConfirm':
					case 'Succeeded':
						$this->payment_complete( $order, $trans_id );
						$this->log( 'TBC Checkout: payment successful.', 'notice' );
						wp_safe_redirect( $this->get_safe_return_url( $order ) );
						exit;

					case 'Failed':
						$this->payment_failed( $order, $trans_id );
						$this->log( 'TBC Checkout: payment failed.', 'notice' );
						wc_add_notice( __( 'Your payment was declined', 'tbc-checkout' ), 'error' );
						wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
						exit;
				}

				$this->log( 'API did not return status Succeeded or Failed.', 'error' );
			} else {
				$this->log( 'API did not return anything.', 'error' );
			}
		} else {
			$this->log( 'Order not found.', 'error' );
		}

		wc_add_notice( __( 'Something went wrong.', 'tbc-checkout' ), 'error' );
		wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
		exit;
	}

	/**
	 * Get return url (order received page) in a safe manner.
	 * https://github.com/woocommerce/woocommerce/issues/22049
	 *
	 * @since 1.0.0-
	 * @param WC_Order $order Order object.
	 * @return string
	 */
	public function get_safe_return_url( $order ) {
		if ( $order->get_user_id() === get_current_user_id() ) {
			return $this->get_return_url( $order );
		} else {
			return wc_get_endpoint_url( 'order-received', '', wc_get_page_permalink( 'checkout' ) );
		}
	}

	/**
	 * Payment complete.
	 *
	 * @since 1.0.0
	 * @param WC_Order $order Order object.
	 * @param string   $transaction_id Transaction ID.
	 * @return bool
	 */
	public function payment_complete( $order, $transaction_id ) {
		if ( $order->payment_complete() ) {
			/* translators: Transaction ID */
			$order->add_order_note( sprintf( __( 'TBC Checkout payment complete (transaction_id: %s)', 'tbc-checkout' ), $transaction_id ) );
			return true;
		}
		return false;
	}

	/**
	 * Payment failed.
	 *
	 * @since 1.0.0
	 * @param WC_Order $order Order object.
	 * @param string   $transaction_id Transaction ID.
	 * @return bool
	 */
	public function payment_failed( $order, $transaction_id ) {
		/* translators: Transaction ID */
		$order_note = sprintf( __( 'TBC Checkout payment failed (transaction_id: %s)', 'tbc-checkout' ), $transaction_id );
		if ( $order->has_status( 'failed' ) ) {
			$order->add_order_note( $order_note );
			return true;
		} else {
			return $order->update_status( 'failed', $order_note );
		}
	}

	/**
	 * Convert locale to API lang designation.
	 * e.g. ka_GE -> GE.
	 *
	 * @since 2.0.0
	 * @param string $locale Locale such as en_US.
	 * @return string
	 */
	public function locale_to_lang( $locale ) {
		return $this->supported_languages[ $locale ] ?? 'KA';
	}

	/**
	 * Generate client accounts html.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function generate_client_accounts_html() {

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php esc_html_e( 'Client accounts:', 'tbc-checkout' ); ?></th>
			<td class="forminp" id="tbc_checkout_client_accounts">
				<div class="wc_input_table_wrapper">
					<table class="widefat wc_input_table sortable" cellspacing="0">
						<thead>
							<tr>
								<th class="sort">&nbsp;</th>
								<th><?php esc_html_e( 'Currency', 'tbc-checkout' ); ?></th>
								<th><?php esc_html_e( 'Client Id', 'tbc-checkout' ); ?></th>
								<th><?php esc_html_e( 'Client Secret', 'tbc-checkout' ); ?></th>
							</tr>
						</thead>
						<tbody class="accounts">
							<?php
							$i = -1;
							if ( $this->client_accounts ) {
								foreach ( $this->client_accounts as $account ) {
									$i++;

									echo '<tr class="client_account">
										<td class="sort"></td>
										<td><input type="text" value="' . esc_attr( wp_unslash( $account['currency'] ) ) . '" name="tbc_checkout_client_account_currencies[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['client_id'] ) . '" name="tbc_checkout_client_account_client_ids[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['client_secret'] ) . '" name="tbc_checkout_client_account_client_secrets[' . esc_attr( $i ) . ']" /></td>
									</tr>';
								}
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="7"><a href="#" class="add button"><?php esc_html_e( '+ Add account', 'tbc-checkout' ); ?></a> <a href="#" class="remove_rows button"><?php esc_html_e( 'Remove selected account(s)', 'tbc-checkout' ); ?></a></th>
							</tr>
						</tfoot>
					</table>
				</div>
				<script type="text/javascript">
					jQuery(function() {
						jQuery('#tbc_checkout_client_accounts').on( 'click', 'a.add', function(){

							var size = jQuery('#tbc_checkout_client_accounts').find('tbody .client_account').length;

							jQuery('<tr class="client_account">\
									<td class="sort"></td>\
									<td><input type="text" name="tbc_checkout_client_account_currencies[' + size + ']" /></td>\
									<td><input type="text" name="tbc_checkout_client_account_client_ids[' + size + ']" /></td>\
									<td><input type="text" name="tbc_checkout_client_account_client_secrets[' + size + ']" /></td>\
								</tr>').appendTo('#tbc_checkout_client_accounts table tbody');

							return false;
						});
					});
				</script>
			</td>
		</tr>
		<?php
		return ob_get_clean();

	}

	/**
	 * Save client accounts table.
	 *
	 * @since 2.0.0
	 */
	public function save_client_accounts() {

		$accounts = [];

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verification already handled in WC_Admin_Settings::save()
		if ( isset( $_POST['tbc_checkout_client_account_currencies'], $_POST['tbc_checkout_client_account_client_ids'], $_POST['tbc_checkout_client_account_client_secrets'] ) ) {

			$currencies     = wc_clean( wp_unslash( $_POST['tbc_checkout_client_account_currencies'] ) );
			$client_ids     = wc_clean( wp_unslash( $_POST['tbc_checkout_client_account_client_ids'] ) );
			$client_secrets = wc_clean( wp_unslash( $_POST['tbc_checkout_client_account_client_secrets'] ) );
			// phpcs:enable

			foreach ( $client_ids as $i => $id ) {

				if ( ! $currencies[ $i ] && ! $id && ! $client_secrets[ $i ] ) {
					continue;
				}

				$accounts[] = [
					'currency'      => $currencies[ $i ],
					'client_id'     => $id,
					'client_secret' => $client_secrets[ $i ],
				];
			}
		}

		update_option( sprintf( 'woocommerce_%s_client_accounts', $this->id ), $accounts );
	}

}
